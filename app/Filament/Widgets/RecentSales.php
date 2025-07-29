<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class RecentSales extends TableWidget
{
    protected static ?string $heading = 'Penjualan Terbaru';

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Sale::query()
            ->with(['salesItems', 'customer']) // include relasi
            ->latest()
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('d M Y, H:i'),

            TextColumn::make('customer.name') // <- ambil dari relasi
                ->label('Customer')
                ->placeholder('-'),

            TextColumn::make('total')
                ->label('Total Harga')
                ->getStateUsing(function ($record) {
                    return $record->salesItems->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });
                })
                ->money('IDR', locale: 'id'),
        ];
    }
}
