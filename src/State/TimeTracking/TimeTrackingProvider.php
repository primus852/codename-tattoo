<?php

namespace App\State\TimeTracking;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\TimeTracking\TimeTrackingDTO;
use App\Dto\TimeTracking\TimeTrackingListDTO;
use App\Entity\Price;
use App\Entity\ConfigRateHours;
use App\Entity\TimeTracking;
use App\Exception\TimeTrackingNotFoundException;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;

readonly class TimeTrackingProvider implements ProviderInterface
{

    public function __construct(
        private ProviderInterface      $itemProvider,
        private CollectionProvider     $collectionProvider,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @throws TimeTrackingNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        $repo = $this->entityManager->getRepository(Price::class);
        $configuredPrices = $repo->findAll();

        if ($operation instanceof CollectionOperationInterface) {
            $timeTrackings = $this->collectionProvider->provide($operation, $uriVariables, $context);
            return new TimeTrackingListDTO($timeTrackings, $configuredPrices);
        }

        /* @var TimeTracking $timeTracking */
        $timeTracking = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($timeTracking === null) {
            throw new TimeTrackingNotFoundException('TIMETRACKING_NOT_FOUND');
        }
        $slots = ConfigService::getRateHoursBetweenDates(
            $timeTracking->getServiceStart(),
            $timeTracking->getServiceEnd(),
            $configuredPrices
        );


        return new TimeTrackingDTO($timeTracking, $slots);
    }
}
