<?php

namespace App\State\Config;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Config\ConfigRateHoursDto;
use App\Dto\Config\ConfigRateHoursSlotsDto;
use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;

readonly class ConfigRateHoursProcessor implements ProcessorInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws InvalidTimeConfigException
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
        }

        return new ConfigRateHoursDto($data);
    }

    /**
     * @throws InvalidTimeConfigException
     */
    private function _createNewConfigRateHour(mixed $data): ConfigRateHours
    {
        $repo = $this->entityManager->getRepository(ConfigRateHours::class);
        $existing = $repo->findAll();
        $crh = ConfigService::createNewConfigRateHour($data->hourFrom, $data->hourTo, $data->priceNet, $data->category, $existing);
        $repo->save($crh, true);

        return $crh;
    }
}
