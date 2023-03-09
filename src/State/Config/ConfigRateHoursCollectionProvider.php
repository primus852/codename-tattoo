<?php

namespace App\State\Config;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Config\ConfigRateHoursListDto;

readonly class ConfigRateHoursCollectionProvider implements ProviderInterface
{
    public function __construct(
        private CollectionProvider $collectionProvider
    )
    {
    }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object|array|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
        return new ConfigRateHoursListDto($entities);
    }
}
