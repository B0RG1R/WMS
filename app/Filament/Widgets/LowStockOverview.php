<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class LowStockOverview extends BaseWidget
{

    protected int|string|array $columnSpan = 3;
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        $lowStockCount = Product::whereColumn('stock', '<', 'minimum_stock')->count();

        return [
            Card::make('⚠️ Please Restock', $lowStockCount)
                ->description('Produk di bawah minimum stok')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success')
                ->url(route('filament.admin.resources.products.index', [
                    'tableFilters[LowStock]' => true,
                ]))
                ->extraAttributes(['class' => 'cursor-pointer']),
        ];
    }
}
