<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Ventas Totales', '$' . number_format(Order::sum('total_amount'), 2))
                ->description('Ingresos históricos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Pedidos Nuevos', Order::where('status', OrderStatus::NEW)->count())
                ->description('Pendientes de procesar')
                ->color('warning'),
            Stat::make('Clientes Registrados', User::where('role', 'customer')->count()),
        ];
    }
}
