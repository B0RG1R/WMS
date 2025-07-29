<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivingResource\Pages;
use App\Models\Receiving;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
<<<<<<< HEAD
=======
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Collection;
>>>>>>> 82fdca1 (progres dashboard)

class ReceivingResource extends Resource
{
    protected static ?string $model = Receiving::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('purchase_id')
                    ->label('Purchase Order')
                    ->options(Purchase::all()->pluck('invoice_number', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $purchase = Purchase::with('items.product')->find($state);
<<<<<<< HEAD
                        if ($purchase) {
                            $set('items', collect($purchase->items)->map(function ($item) {
                                return [
                                    'product_name' => $item->product->name ?? '-',
                                    'ordered_qty' => $item->quantity,
                                    'received_qty' => $item->quantity,
                                    'purchase_item_id' => $item->id,
                                ];
                            })->toArray());
=======

                        if ($purchase) {
                            $items = collect($purchase->items)->map(function ($item) {
                                return [
                                    'purchase_item_id' => $item->id,
                                    'product_name' => $item->product->name ?? '-',
                                    'ordered_qty' => $item->quantity,
                                    'received_qty' => $item->quantity,
                                ];
                            });

                            $set('items', $items->toArray());
>>>>>>> 82fdca1 (progres dashboard)
                        } else {
                            $set('items', []);
                        }
                    })
                    ->required(),

                DatePicker::make('received_date')
                    ->default(now())
                    ->required(),

                TextInput::make('received_by')
<<<<<<< HEAD
=======
                    ->label('Received By')
>>>>>>> 82fdca1 (progres dashboard)
                    ->required()
                    ->maxLength(255),

                Repeater::make('items')
                    ->label('Receiving Items')
<<<<<<< HEAD
                    ->relationship()
                    ->schema([
=======
                    ->schema([
                        Hidden::make('purchase_item_id'),
                        Hidden::make('product_name'),

>>>>>>> 82fdca1 (progres dashboard)
                        TextInput::make('product_name')
                            ->label('Product')
                            ->disabled(),

                        TextInput::make('ordered_qty')
                            ->label('Qty Ordered')
                            ->disabled(),

                        TextInput::make('received_qty')
                            ->label('Qty Received')
                            ->numeric()
                            ->required(),
<<<<<<< HEAD

                        TextInput::make('purchase_item_id')
                            ->hidden(),
=======
>>>>>>> 82fdca1 (progres dashboard)
                    ])
                    ->columns(3)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchase.invoice_number')
                    ->label('Invoice')
                    ->searchable(),

                Tables\Columns\TextColumn::make('received_date')
                    ->date(),

                Tables\Columns\TextColumn::make('received_by'),

                Tables\Columns\TextColumn::make('created_at')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceivings::route('/'),
            'create' => Pages\CreateReceiving::route('/create'),
            'edit' => Pages\EditReceiving::route('/{record}/edit'),
        ];
    }
}
