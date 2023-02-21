<?php

namespace App\Dto\Config;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
// CONTINUE HERE!!!

final class ConfigRateWeekToHoursRequestDto
{
    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public \DateTimeImmutable $datetimeFrom;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public \DateTimeImmutable $datetimeTo;

}
