<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
<<<<<<< HEAD
=======
use App\Filament\Resources\SaleResource\RelationManagers;
>>>>>>> 82fdca1 (progres dashboard)
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
>>>>>>> 82fdca1 (progres dashboard)
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 20;
<<<<<<< HEAD
=======


>>>>>>> 82fdca1 (progres dashboard)
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

<<<<<<< HEAD
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Name'),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->required()
                            ->unique('customers', 'phone'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique('customers', 'email'),

                        TextInput::make('address')
                            ->label('Address'),
                    ])
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $customer = \App\Models\Customer::find($state);
                            if ($customer) {
                                $set('customer_name', $customer->name);
                            }
                        } else {
                            $set('customer_name', null);
                        }
                    }),
=======
                TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255),
>>>>>>> 82fdca1 (progres dashboard)

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
<<<<<<< HEAD
                Tables\Columns\TextColumn::make('customer.name')->label('Customer'),
=======
                Tables\Columns\TextColumn::make('customer_name')->label('Customer'),
>>>>>>> 82fdca1 (progres dashboard)
                Tables\Columns\TextColumn::make('products')
                    ->label('Products')
                    ->getStateUsing(fn ($record) => $record->items->map(fn ($item) => $item->product->name ?? '-')->implode(', '))
                    ->limit(50),
                Tables\Columns\TextColumn::make('total_amount')
<<<<<<< HEAD
                    ->label('Total')
                    ->money('IDR', true),
=======
                ->label('Total')
                ->money('IDR', true),
            ])
            ->filters([
                //
>>>>>>> 82fdca1 (progres dashboard)
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
<<<<<<< HEAD
        return [];
=======
        return [
            //
        ];
>>>>>>> 82fdca1 (progres dashboard)
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
