<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'Active';
    case InActive = 'InActive';

    public function badgeClass(): string
    {
        return $this === self::Active ? 'badge-success' : 'badge-danger';
    }
}
