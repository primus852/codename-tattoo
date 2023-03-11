<?php

namespace App\State\Price;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Price\PriceDTO;
use App\Dto\Price\PriceInRangeResponseDTO;
use App\Dto\Price\ConfigRateHoursDto;
use App\Dto\Price\ConfigRateHoursSlotsDto;
use App\Entity\Price;
use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;

readonly class PriceInRangeProcessor implements ProcessorInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PriceInRangeResponseDTO
    {

        $repo = $this->entityManager->getRepository(Price::class);
        $configuredPrices = $repo->findAll();

        $minutes = ConfigService::getRateHoursBetweenDates($data->datetimeFrom, $data->datetimeTo, $configuredPrices);

        return new PriceInRangeResponseDTO($data->datetimeFrom, $data->datetimeTo, $minutes);
    }
}
