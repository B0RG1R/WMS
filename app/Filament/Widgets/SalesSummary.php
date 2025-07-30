<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class SalesSummary extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Penjualan Hari Ini', 'Rp' . number_format(Sale::whereDate('sale_date', today())->sum('total_amount'), 0, ',', '.')),
            Card::make('Penjualan Bulan Ini', 'Rp' . number_format(Sale::whereMonth('sale_date', now()->month)->sum('total_amount'), 0, ',', '.')),
            Card::make('Total Keseluruhan', 'Rp' . number_format(Sale::sum('total_amount'), 0, ',', '.')),
        ];
    }
}
