<?php

namespace App\Dto\User;

final class UsersDeleteResponseDto
{
    public array $deleted;

    public function __construct(array $ids)
    {
        $this->deleted = $ids;
    }
}
