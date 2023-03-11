<?php

namespace App\State\Price;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Price\PriceListDTO;

readonly class PriceCollectionProvider implements ProviderInterface
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
        return new PriceListDTO($entities);
    }
}
