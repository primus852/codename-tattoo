<?php

namespace App\Dto\TimeTracking;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class TimeTrackingUpdateStatusDTO
{
    #[ApiProperty(
        openapiContext: [
            'description' => 'Uuid of Time Tracking to override/reset'
        ]
    )]
    #[Assert\NotBlank]
    public Uuid $timeTrackingId;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'enum' => ['OPEN', 'NONE', 'NONE_SHOW', 'FINISHED'],
            'example' => 'OPEN'
        ]
    )]
    #[Assert\NotBlank]
    public string $newStatus;
}
