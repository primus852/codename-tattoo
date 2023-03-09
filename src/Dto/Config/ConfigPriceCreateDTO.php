<?php

namespace App\Dto\Config;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class ConfigPriceCreateDTO
{
    #[Assert\NotBlank]
    #[Assert\Time]
    #[Groups(['write', 'read'])]
    public string $timeFrom;

    #[Assert\NotBlank]
    #[Assert\Time]
    #[Groups(['write', 'read'])]
    public string $timeTo;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['write', 'read'])]
    public float $priceNet;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['write', 'read'])]
    public float $weekDay;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $category;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $name;
}
