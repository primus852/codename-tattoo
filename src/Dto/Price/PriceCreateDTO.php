<?php

namespace App\Dto\Price;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class PriceCreateDTO
{
    #[Assert\NotBlank]
    #[Assert\Time]
    #[Groups(['price'])]
    public string $timeFrom;

    #[Assert\NotBlank]
    #[Assert\Time]
    #[Groups(['price'])]
    public string $timeTo;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['price'])]
    public float $priceNet;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['price'])]
    public float $weekDay;

    #[Assert\NotBlank]
    #[Groups(['price'])]
    public string $category;

    #[Assert\NotBlank]
    #[Groups(['price'])]
    public string $name;
}
