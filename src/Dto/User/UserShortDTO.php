<?php

namespace App\Dto\User;

use App\Entity\User;

class UserShortDTO
{
    public string $id;
    public string $email;
    public string $code;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getUserIdentifier();
        $this->code = $user->getCode();
    }

}
