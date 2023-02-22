<?php

namespace App\State\Config;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Config\ConfigRateHoursDto;
use App\Dto\Config\ConfigRateHoursSlotsDto;
use App\Entity\ConfigRateHours;
use App\Entity\ConfigWeekDays;
use App\Exception\ConfigNotFoundException;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;

readonly class ConfigRateHoursProcessor implements ProcessorInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws InvalidTimeConfigException|ConfigNotFoundException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ConfigRateHoursDto|ConfigRateHoursSlotsDto
    {
        if ($operation instanceof Post) {
            if ($context['operation']->getUriTemplate() === '/config/rate-hour') {
                $crh = $this->_createNewConfigRateHour($data);
                return new ConfigRateHoursDto($crh);
            }

            if ($context['operation']->getUriTemplate() === '/process/rate-hour/combined') {
                $repo = $this->entityManager->getRepository(ConfigRateHours::class);
                $configuredRateHours = $repo->findAll();
                return new ConfigRateHoursSlotsDto($data->datetimeFrom, $data->datetimeTo, $configuredRateHours);
            }

            if ($context['operation']->getUriTemplate() === '/process/rate-hour/assign-days-of-week') {
                $cfgRateHour = $this->_attachRateHourToWeekDays($data);
                return new ConfigRateHoursDto($cfgRateHour);
            }

        }

        return new ConfigRateHoursDto($data);
    }

    /**
     * @param mixed $data
     * @return ConfigRateHours
     * @throws ConfigNotFoundException
     */
    private function _attachRateHourToWeekDays(mixed $data): ConfigRateHours
    {
        $repoRateHours = $this->entityManager->getRepository(ConfigRateHours::class);
        $configuredRateHour = $repoRateHours->findOneBy(array(
            'id' => $data->configRateHourId
        ));

        if ($configuredRateHour === null) {
            throw new ConfigNotFoundException('RATEHOURS_NOT_FOUND');
        }

        $repoWeekDays = $this->entityManager->getRepository(ConfigWeekDays::class);
        $weekDays = $repoWeekDays->findBy(array(
            'dayOfWeek' => $data->configRateWeekDays
        ));

        if ($weekDays === null) {
            throw new ConfigNotFoundException('WEEKDAY_NOT_FOUND');
        }

        foreach ($weekDays as $weekDay) {
            $configuredRateHour->addConfigWeekDay($weekDay);
            $weekDay->addConfigRateHour($configuredRateHour);
            $repoRateHours->save($configuredRateHour, true);
            $repoWeekDays->save($weekDay, true);
        }

        return $configuredRateHour;

    }

    /**
     * @throws InvalidTimeConfigException
     */
    private function _createNewConfigRateHour(mixed $data): ConfigRateHours
    {
        $repo = $this->entityManager->getRepository(ConfigRateHours::class);
        $existing = $repo->findAll();
        $crh = ConfigService::createNewConfigRateHour($data->hourFrom, $data->hourTo, $data->priceNet, $data->category, $data->daysOfWeek, $existing);
        $repo->save($crh, true);

        return $crh;
    }
}
