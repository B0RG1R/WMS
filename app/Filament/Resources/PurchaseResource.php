<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_number')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('purchase_date')
                    ->required(),

            // Supplier dengan create option
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->email(),
                        TextInput::make('phone'),
                    ])
                    ->required(),

            // Purchase items
                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                Select::make('category_id')
                                    ->label('Kategori Produk')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')->required()->label('Nama Kategori Baru'),
                                    ])
                                    ->required(),
                                TextInput::make('price')->required()->numeric()->minValue(0),
                                TextInput::make('stock')->label('Initial Stock')->default(0),
                            ])
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        TextInput::make('price')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(3)
                    ->defaultItems(1)
                    ->label('Purchase Items'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')->date(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('items_count')->counts('items'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);

    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
