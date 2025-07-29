<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full'; // biar melebar satu baris

    protected function getTableQuery(): Builder|Relation|null
    {
        return Product::query()
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc'); // biar stok terendah muncul dulu
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Produk')
                ->sortable()
                ->searchable(),

            TextColumn::make('stock')
                ->label('Stok Sekarang')
                ->numeric()
                ->color('danger'), // warna merah biar kesan urgent

            TextColumn::make('minimum_stock')
                ->label('Stok Minimum')
                ->numeric()
                ->color('gray'),
        ];
    }
}
