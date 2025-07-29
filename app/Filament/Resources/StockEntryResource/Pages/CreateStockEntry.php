<?php

namespace App\Filament\Resources\StockEntryResource\Pages;

use App\Filament\Resources\StockEntryResource;
use App\Models\StockEntry;
use Filament\Resources\Pages\CreateRecord;

class CreateStockEntry extends CreateRecord
{
    protected static string $resource = StockEntryResource::class;

    protected function handleRecordCreation(array $data): StockEntry
    {
        $record = StockEntry::create($data);

        // Tambah stok ke produk
        $product = $record->product;
        $product->increment('stock', $record->quantity);

        return $record;
    }
}
