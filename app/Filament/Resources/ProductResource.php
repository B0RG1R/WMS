<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // === Info Produk ===
            Forms\Components\TextInput::make('name')
                ->label('Product Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('sku')
                ->label('SKU')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(100),

            // === Stok ===
            Forms\Components\TextInput::make('stock')
                ->label('Stock')
                ->numeric()
                ->minValue(0)
                ->required()
                ->default(0),

            Forms\Components\TextInput::make('minimum_stock')
                ->label('Minimum Stock')
                ->numeric()
                ->minValue(0)
                ->required()
                ->default(0),

            // === Harga ===
            Forms\Components\TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->required(),

            // === Kategori ===
            Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->nullable(),

            // === Deskripsi ===
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(3)
                ->maxLength(1000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock'),

                Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Min Stock'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('idr'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Filter by Category')
                    ->relationship('category', 'name'),

                Filter::make('low_stock')
                    ->label('Perlu Restock')
                    ->query(fn (Builder $query) => $query->whereColumn('stock', '<', 'minimum_stock')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
