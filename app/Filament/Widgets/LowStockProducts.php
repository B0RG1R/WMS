<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class LowStockProducts extends BaseWidget
{
    protected static ?string $heading = 'Perlu Restock';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->whereColumn('stock', '<', 'minimum_stock');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Product Name')->searchable(),
            Tables\Columns\TextColumn::make('stock')->label('Stock'),
            Tables\Columns\TextColumn::make('minimum_stock')->label('Min Stock'),
            Tables\Columns\TextColumn::make('category.name')->label('Category'),
            Tables\Columns\TextColumn::make('price')->money('idr'),
        ];
    }
}
