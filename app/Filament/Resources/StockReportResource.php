<?php

namespace App\Filament\Resources;

use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use App\Exports\StockReportExport;
use App\Filament\Resources\StockReportResource\Pages;
use App\Exports\StockReportExport as StockExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;

class StockReportResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 30;
    protected static ?string $label = 'Stock Report';

    public static function form(Form $form): Form
    {
        return $form; // Read-only, tidak menggunakan form
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->numeric(),

                TextColumn::make('minimum_stock')
                    ->label('Min. Stock')
                    ->sortable()
                    ->numeric(),

                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if ($record->stock <= 0) {
                            return '❌ Out of Stock';
                        } elseif ($record->stock <= $record->minimum_stock) {
                            return '⚠️ Low';
                        } else {
                            return '✅ OK';
                        }
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->stock <= 0) {
                            return 'danger';
                        } elseif ($record->stock <= $record->minimum_stock) {
                            return 'warning';
                        } else {
                            return 'success';
                        }
                    }),
            ])
            ->filters([
                // ✅ Filter Kategori Produk
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),

                // ✅ Filter Status Stock
                Filter::make('stock_status')
                    ->label('Stock Status')
                    ->form([
                        Select::make('value')
                            ->label('Status')
                            ->options([
                                'out' => '❌ Out of Stock',
                                'low' => '⚠️ Low Stock',
                                'ok'  => '✅ OK',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        if (($data['value'] ?? null) === 'out') {
                            $query->where('stock', '<=', 0);
                        } elseif ($data['value'] === 'low') {
                            $query->where('stock', '>', 0)
                                  ->whereColumn('stock', '<=', 'minimum_stock');
                        } elseif ($data['value'] === 'ok') {
                            $query->whereColumn('stock', '>', 'minimum_stock');
                        }
                    }),
            ])
            ->actions([]) // Disable edit/delete
            ->bulkActions([]) // Disable bulk delete
            ->headerActions([
                Action::make('export_stock')
                    ->label('Export Stock Report')
                    ->action(fn () => Excel::download(new StockReportExport, 'stock_report.xlsx'))
                    ->color(null)
                    ->icon(null)
                    ->extraAttributes([
                        'style' => 'background-color: transparent; border: 1px solid white; color: white;',
                        'class' => 'hover:bg-white hover:text-black transition px-4 py-2 rounded-md text-sm',
                    ]),
                ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockReports::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // tidak bisa buat data baru di report
    }
}
