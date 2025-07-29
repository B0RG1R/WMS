<?php
namespace App\Filament\Widgets;

use App\Models\SalesItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan Mingguan';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        foreach (range(6, 0) as $i) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = SalesItem::whereDate('created_at', $date)
                ->get()
                ->sum(fn ($item) => $item->price * $item->quantity);
        }

        return [
            'datasets' => [[
                'label' => 'Penjualan (Rp)',
                'data' => $data,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
