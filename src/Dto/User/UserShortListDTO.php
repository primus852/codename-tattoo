<?php

namespace App\Dto\User;

use ApiPlatform\Doctrine\Orm\Paginator;
use Symfony\Component\Serializer\Annotation\Groups;

class UserShortListDTO
{
    #[Groups(['read'])]
    public array $users = array();

    /**
     * @param Paginator $userPaginator
     */
    public function __construct(Paginator $userPaginator)
    {
        foreach ($userPaginator as $user) {
            $this->users[] = new UserShortDTO($user);
        }
    }
}
