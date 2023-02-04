<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

final class UserCreateDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    public array $roles;

    #[Assert\NotBlank]
    public string $password;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $code;

}
