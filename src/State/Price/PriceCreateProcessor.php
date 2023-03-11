<?php

namespace App\State\Price;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Price\PriceDTO;
use App\Dto\Price\ConfigRateHoursDto;
use App\Dto\Price\ConfigRateHoursSlotsDto;
use App\Entity\Price;
use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;

readonly class PriceCreateProcessor implements ProcessorInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PriceDTO
    {
        $crh = $this->_createNewPrice($data);
        return new PriceDTO($crh);
    }

    /**
     * @throws InvalidTimeConfigException
     */
    private function _createNewPrice(mixed $data): Price
    {
        $repo = $this->entityManager->getRepository(Price::class);
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
