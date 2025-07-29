<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Filament\Resources\StockMovementResource\RelationManagers;
use App\Models\StockMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockMovementsExport;
use Filament\Tables\Actions\ActionGroup;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('movement_date')->label('Tanggal')->required(),
            Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Produk')
                ->searchable()
                ->required(),
            Select::make('movement_type')
                ->options([
                    'in' => 'Masuk',
                    'out' => 'Keluar',
                ])
                ->label('Tipe')
                ->required(),
            TextInput::make('quantity')->label('Jumlah')->numeric()->minValue(1)->required(),
            TextInput::make('reference_number')->label('No. Referensi'),
            Textarea::make('note')->label('Catatan')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('movement_date')->label('Tanggal')->date(),
                Tables\Columns\TextColumn::make('product.name')->label('Produk'),
                Tables\Columns\BadgeColumn::make('movement_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'in',
                        'danger' => 'out',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'in' ? 'Masuk' : 'Keluar'),

                Tables\Columns\TextColumn::make('quantity')->label('Jumlah'),
                Tables\Columns\TextColumn::make('reference_number')->label('Referensi'),
                Tables\Columns\TextColumn::make('note')->label('Catatan')->limit(20),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->since(),
            ])
            ->defaultSort('movement_date', 'desc')
            ->filters([
                SelectFilter::make('movement_type')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                    ])
                    ->label('Tipe')
            ])
            ->headerActions([
                ActionGroup::make([
                    Tables\Actions\Action::make('Export In')
                        ->label('Export Masuk')
                        ->color('success')
                        ->action(fn () => Excel::download(new StockMovementsExport('in'), 'stock_movement_masuk.xlsx')),
                    Tables\Actions\Action::make('Export Out')
                        ->label('Export Keluar')
                        ->color('danger')
                        ->action(fn () => Excel::download(new StockMovementsExport('out'), 'stock_movement_keluar.xlsx')),
                    Tables\Actions\Action::make('Export All')
                        ->label('Export Semua')
                        ->color('gray')
                        ->action(fn () => Excel::download(new StockMovementsExport(), 'stock_movement_semua.xlsx')),
                ])->label('Export Data')->icon('heroicon-o-arrow-down-tray'),
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
            'index' => Pages\ListStockMovements::route('/'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

}
