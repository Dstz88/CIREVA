<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case ORGANIZER = 'organizer';
    case USER = 'user';
    case GUEST = 'guest';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::ORGANIZER => 'Organizer',
            self::USER => 'User',
            self::GUEST => 'Guest',
        };
    }
}
