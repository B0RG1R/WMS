<?php

namespace App\Filament\Resources;

use App\Models\Purchase;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\PurchaseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\PurchaseReportResource\Pages\ListPurchaseReports;

class PurchaseReportResource extends Resource
{
    protected static ?string $model = Purchase::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $label = 'Purchase Report';
    protected static ?int $navigationSort = 32;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('purchase_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('items_list')
                    ->label('Barang')
                    ->getStateUsing(fn ($record) => $record->items->pluck('product.name')->join(', ')),

                TextColumn::make('qty_list')
                    ->label('Qty')
                    ->getStateUsing(fn ($record) => $record->items->pluck('quantity')->join(', ')),

                TextColumn::make('harga_list')
                    ->label('Harga')
                    ->getStateUsing(fn ($record) =>
                        $record->items->pluck('price')
                            ->map(fn ($p) => 'Rp' . number_format($p, 0, ',', '.'))
                            ->join(', ')
                    ),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR', true)
                    ->getStateUsing(fn ($record) =>
                        $record->items->sum(fn ($item) => $item->price * $item->quantity)
                    )
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Pembelian')
                    ),
            ])
            ->filters([
                Tables\Filters\Filter::make('purchase_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('purchase_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('purchase_date', '<=', $data['until']));
                    }),
            ])
            ->headerActions([
                Action::make('export_all')
                    ->label('Export Purchase Report')
                    ->color(null)
                    ->action(function () {
                        return Excel::download(new PurchaseReportExport, 'purchase_report.xlsx');
                    })
                    ->extraAttributes([
                        'style' => 'background-color: transparent !important; border: 1px solid white; color: white;',
                        'class' => 'hover:bg-white hover:text-black transition px-4 py-2 rounded-md text-sm',
                    ]),
            ])
            ->defaultSort('purchase_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseReports::route('/'),
        ];
    }
}
