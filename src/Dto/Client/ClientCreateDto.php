<?php

namespace App\Dto\Client;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ClientCreateDto
{
    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $name;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $nameShort;
}
