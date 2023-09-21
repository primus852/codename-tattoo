<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = "ADMIN";
    case USER = "USER";
    case FINANCE = "FINANCE";
    case APPROVER = "APPROVER";

    public static function isValid(string $role): bool
    {
        $validValues = array_map(fn($case) => $case->value, self::cases());
        return in_array($role, $validValues);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
