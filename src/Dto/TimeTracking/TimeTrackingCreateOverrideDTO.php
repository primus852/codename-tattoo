<?php

namespace App\Dto\TimeTracking;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class TimeTrackingCreateOverrideDTO
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
            'description' => 'Uuid of Config Rate Hour'
        ]
    )]
    public Uuid $overrideToPriceId;

    #[ApiProperty(
        openapiContext: [
            'description' => 'If reset is true, rateHourId is ignored'
        ]
    )]
    public bool $reset;
}
