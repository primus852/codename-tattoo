<?php

namespace App\State\Config;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Config\ConfigRateHoursDto;
use App\Exception\ConfigNotFoundException;

readonly class ConfigRateHoursProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface  $itemProvider
    )
    {
    }

    /**
     * @throws ConfigNotFoundException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($entity === null) {
            throw new ConfigNotFoundException('RATEHOUR_NOT_FOUND');
        }

        return new ConfigRateHoursDto($entity);
    }
}
