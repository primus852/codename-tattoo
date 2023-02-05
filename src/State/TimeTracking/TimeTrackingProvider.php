<?php

namespace App\State\TimeTracking;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\TimeTracking\TimeTrackingDto;
use App\Dto\TimeTracking\TimeTrackingListDto;
use App\Exception\TimeTrackingNotFoundException;

readonly class TimeTrackingProvider implements ProviderInterface
{

    public function __construct(private ProviderInterface $itemProvider, private CollectionProvider $collectionProvider)
    {
    }

    /**
     * @throws TimeTrackingNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        if ($operation instanceof CollectionOperationInterface) {
            $timeTrackings = $this->collectionProvider->provide($operation, $uriVariables, $context);
            return new TimeTrackingListDto($timeTrackings);
        }

        $timeTracking = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($timeTracking === null) {
            throw new TimeTrackingNotFoundException('TIMETRACKING_NOT_FOUND');
        }

        return new TimeTrackingDto($timeTracking);
    }
}
