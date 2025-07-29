<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 20;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_number')
                    ->required()
                    ->unique(ignoreRecord: true),

                DatePicker::make('sale_date')
                    ->required(),

                TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255),

                Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->required(),

                        TextInput::make('price')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(3)
                    ->required()
                    ->label('Sale Items'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Invoice'),
                Tables\Columns\TextColumn::make('sale_date')->date()->label('Date'),
                Tables\Columns\TextColumn::make('customer_name')->label('Customer'),
                Tables\Columns\TextColumn::make('products')
                    ->label('Products')
                    ->getStateUsing(fn ($record) => $record->items->map(fn ($item) => $item->product->name ?? '-')->implode(', '))
                    ->limit(50),
                Tables\Columns\TextColumn::make('total_amount')
                ->label('Total')
                ->money('IDR', true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
