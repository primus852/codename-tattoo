<?php

namespace App\Dto\Config;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ConfigRateHoursRequestSlotsDto
{
    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public \DateTimeImmutable $datetimeFrom;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public \DateTimeImmutable $datetimeTo;

}
