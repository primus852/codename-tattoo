<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UsersDeleteDto
{
    #[Assert\NotBlank]
    #[Groups(['ids'])]
    public array $ids = [];
}
