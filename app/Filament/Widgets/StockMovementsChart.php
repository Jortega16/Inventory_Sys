<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Modules\Warehouse\Models\StockMovement;

class StockMovementsChart extends ChartWidget
{
    protected static ?string $heading = 'Movimiento de existencias';

    protected static ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('warehouse.view') ?? false;
    }

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn (int $i) => Carbon::today()->subDays($i));

        $movements = StockMovement::query()
            ->selectRaw('DATE(created_at) as day, SUM(ABS(quantity_delta)) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('day')
            ->pluck('total', 'day');

        return [
            'datasets' => [
                [
                    'label' => 'Unidades movidas',
                    'data' => $days->map(fn (Carbon $day) => (int) ($movements[$day->toDateString()] ?? 0))->all(),
                    'backgroundColor' => '#f59e0b',
                    'borderRadius' => 6,
                    'maxBarThickness' => 32,
                ],
            ],
            'labels' => $days->map(fn (Carbon $day) => $day->translatedFormat('D'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}
