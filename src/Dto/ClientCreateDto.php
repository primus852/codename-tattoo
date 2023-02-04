<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ClientCreateDto
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $nameShort;
}
