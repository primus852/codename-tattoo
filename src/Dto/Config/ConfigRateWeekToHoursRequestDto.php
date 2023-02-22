<?php

namespace App\Dto\Config;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final class ConfigRateWeekToHoursRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[Groups(['write', 'read'])]
    public Uuid $configRateHourId;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['write', 'read'])]
    public array $configRateWeekDays;

}
