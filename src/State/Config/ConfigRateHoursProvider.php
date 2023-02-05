<?php

namespace App\State\Config;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Config\ConfigRateHoursDto;
use App\Dto\Config\ConfigRateHoursListDto;
use App\Exception\ConfigNotFoundException;

readonly class ConfigRateHoursProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface  $itemProvider,
        private CollectionProvider $collectionProvider)
    {
    }

    /**
     * @throws ConfigNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
            return new ConfigRateHoursListDto($entities);
        }

        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($entity === null) {
            throw new ConfigNotFoundException('RATEHOUR_NOT_FOUND');
        }

        return new ConfigRateHoursDto($entity);
    }
}
