<?php

namespace App\Filament\Resources\StockReportResource\Pages;

use App\Filament\Resources\StockReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockReport extends CreateRecord
{
    public static function canCreate(): bool
    {
        return false;
    }
}
