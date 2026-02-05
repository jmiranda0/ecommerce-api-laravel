<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Ventas del Año';

    protected function getData(): array
    {
        $data = Order::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )->where('created_at', '>=', Carbon::now()->subYear())
         ->groupBy('month')
         ->orderBy('month')
         ->get();
        return [
            'datasets' => [
                    [
                        'label' => 'Ingresos ($)',
                        'data' => $data->pluck('total')->toArray(),
                        'borderColor' => '#0d9488', // Teal
                    ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
