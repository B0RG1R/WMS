<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;

class InventorySummary extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Jumlah Produk', number_format(Product::count()))
                ->description('Total produk yang tersedia')
                ->color('success'),

            Card::make('Jumlah Kategori', number_format(Category::count()))
                ->description('Kategori produk yang terdaftar')
                ->color('info'),

            Card::make('Jumlah Supplier', number_format(Supplier::count()))
                ->description('Supplier aktif dalam sistem')
                ->color('warning'),
        ];
    }
}
