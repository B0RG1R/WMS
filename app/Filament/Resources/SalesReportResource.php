<?php

namespace App\Filament\Resources;

use App\Models\Sale;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ViewColumn;
use App\Filament\Resources\SalesReportResource\Pages\ListSalesReports;

class SalesReportResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $label = 'Sales Report';
    protected static ?int $navigationSort = 31;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sale_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('items_list')
                    ->label('Produk')
                    ->getStateUsing(function ($record) {
                        return $record->items->pluck('product.name')->join(', ');
                    }),

                TextColumn::make('qty_list')
                    ->label('Qty')
                    ->getStateUsing(function ($record) {
                        return $record->items->pluck('quantity')->join(', ');
                    }),

                TextColumn::make('harga_list')
                    ->label('Harga')
                    ->getStateUsing(function ($record) {
                        return $record->items->pluck('price')->map(fn ($p) => 'Rp' . number_format($p, 0, ',', '.'))->join(', ');
                    }),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR', true)
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Penjualan')),
            ])
            ->filters([
                Tables\Filters\Filter::make('sale_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('sale_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('sale_date', '<=', $data['until']));
                    }),
            ])
            ->headerActions([
                Action::make('export_all')
                    ->label('Export Sales Report')
                    ->color(null)
                    ->action(fn () => Excel::download(new SalesExport, 'sales_report.xlsx'))
                    ->extraAttributes([
                        'style' => 'background-color: transparent !important; border: 1px solid white; color: white;',
                        'class' => 'hover:bg-white hover:text-black transition px-4 py-2 rounded-md text-sm',

                    ]),
            ])
            ->defaultSort('sale_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalesReports::route('/'),
        ];
    }
}