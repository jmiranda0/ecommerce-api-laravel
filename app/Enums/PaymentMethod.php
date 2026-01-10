<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case STRIPE = 'stripe';
    case CASH = 'cash';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
