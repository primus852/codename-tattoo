<?php

namespace App\Dto\Client;

use Symfony\Component\Validator\Constraints as Assert;

final class ClientCreateDto
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $nameShort;
}
