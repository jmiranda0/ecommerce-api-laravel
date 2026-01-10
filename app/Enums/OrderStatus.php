<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::NEW => 'Nuevo',
            self::PROCESSING => 'Procesando',
            self::SHIPPED => 'Enviado',
            self::DELIVERED => 'Entregado',
            self::CANCELLED => 'Cancelado',
        };
    }
}
