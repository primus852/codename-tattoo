<?php

namespace App\Dto\User;

use App\Entity\User;

class UserInfoShortDto
{
    public string $email;
    public string $id;
    public array $roles;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getUserIdentifier();
        $this->roles = $user->getRoles();
    }

}
