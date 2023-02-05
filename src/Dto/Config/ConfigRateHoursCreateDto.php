<?php

namespace App\Dto\Config;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ConfigRateHoursCreateDto
{
    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $hourFrom;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $hourTo;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['write', 'read'])]
    public float $priceNet;

}
