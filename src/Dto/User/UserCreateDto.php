<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UserCreateDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['write', 'read'])]
    public string $email;

    #[Groups(['write', 'read'])]
    public array $roles;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $password;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $name;

    #[Assert\NotBlank]
    #[Groups(['write', 'read'])]
    public string $code;

}
