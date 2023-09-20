<?php

namespace App\Dto\User;

use App\Entity\User;

class UserShortDTO
{
    public string $id;
    public string $name;
    public string $email;
    public string $code;
    public array $roles;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->name = $user->getName();
        $this->email = $user->getUserIdentifier();
        $this->code = $user->getCode();
        $this->roles = $this->_prettifyRoles($user->getRoles());
    }

    /**
     * @param array $roles
     * @return array
     */
    private function _prettifyRoles(array $roles): array
    {
        $prettifiedRoles = [];
        foreach ($roles as $role) {
            $prettifiedRoles[] = ucfirst(strtolower(str_replace('ROLE_', '', $role)));
        }

        return $prettifiedRoles;
    }

}
