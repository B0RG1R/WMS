<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Sale;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        // Pastikan relasi items dimuat
        $this->record->load('items.product');

        // Panggil method untuk update stok & simpan StockMovement
        $this->record->finalizeStockMovement();
    }
}

