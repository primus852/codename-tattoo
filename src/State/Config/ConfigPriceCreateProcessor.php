<?php

namespace App\State\Config;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Config\ConfigPriceDTO;
use App\Dto\Config\ConfigRateHoursDto;
use App\Dto\Config\ConfigRateHoursSlotsDto;
use App\Entity\ConfigPrice;
use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;

readonly class ConfigPriceCreateProcessor implements ProcessorInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ConfigPriceDTO
    {
        $crh = $this->_createNewPrice($data);
        return new ConfigPriceDTO($crh);
    }

    /**
     * @throws InvalidTimeConfigException
     */
    private function _createNewPrice(mixed $data): ConfigPrice
    {
        $repo = $this->entityManager->getRepository(ConfigPrice::class);
        $existing = $repo->findAll();
        $crh = ConfigService::createNewPrice(
            $data->timeFrom,
            $data->timeTo,
            $data->priceNet,
            $data->category,
            $data->name,
            $data->weekDay,
            $existing
        );
        $repo->save($crh, true);

        return $crh;
    }
}
