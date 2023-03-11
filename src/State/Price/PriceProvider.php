<?php

namespace App\State\Price;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Price\PriceDTO;
use App\Dto\Price\ConfigPriceListDTO;
use App\Exception\PriceNotFoundException;

readonly class PriceProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $itemProvider
    )
    {
    }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return PriceDTO
     * @throws PriceNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PriceDTO
    {
        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($entity === null) {
            throw new PriceNotFoundException('PRICE_NOT_FOUND');
        }

        return new PriceDTO($entity);
    }
}
