<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockEntryResource\Pages;
use App\Filament\Resources\StockEntryResource\RelationManagers;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;

class StockEntryResource extends Resource
{
    protected static ?string $model = StockEntry::class;
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('entry_date')->label('Tanggal Masuk')->required(),
            Select::make('product_id')
                ->label('Produk')
                ->relationship('product', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')->label('Nama Produk')->required(),
                    Forms\Components\TextInput::make('sku')->label('SKU'),
                    Forms\Components\TextInput::make('price')->label('Harga')->numeric(),
                    // Tambahin field lain kalau mau (misalnya kategori, dll)
                    Forms\Components\Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable(),
                ]),
            TextInput::make('quantity')->numeric()->minValue(1)->required(),
            TextInput::make('reference_number')->label('No. Referensi'),
            Textarea::make('note')->label('Catatan')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('entry_date')->label('Tanggal Masuk')->date(),
            Tables\Columns\TextColumn::make('product.name')->label('Products'),
            Tables\Columns\TextColumn::make('quantity')->label('stock'),
            Tables\Columns\TextColumn::make('reference_number')->label('SKU'),
            Tables\Columns\TextColumn::make('note')->label('Description')->limit(20),
            Tables\Columns\TextColumn::make('created_at')->label('Created')->since(),
        ])
        ->filters([
            // tambahin filter kalau perlu
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('entry_date', 'desc');
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
            'index' => Pages\ListStockEntries::route('/'),
            'create' => Pages\CreateStockEntry::route('/create'),
            'edit' => Pages\EditStockEntry::route('/{record}/edit'),
        ];
    }
}
