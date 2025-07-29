<?php

namespace App\Filament\Resources\ReceivingResource\Pages;

use App\Filament\Resources\ReceivingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceiving extends EditRecord
{
    protected static string $resource = ReceivingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
