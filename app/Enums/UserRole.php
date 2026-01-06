<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

}
