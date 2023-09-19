<?php

namespace App\Dto\Client;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ClientCreateDto
{
    #[Assert\NotBlank]
    #[Groups(['client'])]
    public string $name;

    #[Assert\NotBlank]
    #[Groups(['client'])]
    public string $nameShort;
}
