<?php

namespace App\State\Config;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Config\ConfigRateHoursDto;
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
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ConfigRateHoursDto
    {
        if ($operation instanceof Post) {
            $crh = $this->_createNewConfigRateHour($data);
            return new ConfigRateHoursDto($crh);
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
        $crh = ConfigService::createNewConfigRateHour($data->hourFrom, $data->hourTo, $data->priceNet, $existing);
        $repo->save($crh, true);

        return $crh;
    }
}
