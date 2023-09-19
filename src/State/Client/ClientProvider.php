<?php

namespace App\State\Client;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Client\ClientDTO;
use App\Exception\ClientNotFoundException;

readonly class ClientProvider implements ProviderInterface
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
     * @return ClientDTO
     * @throws ClientNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ClientDTO
    {
        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($entity === null) {
            throw new ClientNotFoundException('CLIENT_NOT_FOUND');
        }

        return new ClientDTO($entity);
    }
}
