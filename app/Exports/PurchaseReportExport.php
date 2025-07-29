<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Purchase::with('supplier', 'items.product')->get()->map(function ($purchase) {
            return [
                'Date' => \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d'),
                'Supplier' => $purchase->supplier->name ?? '-',
                'Items' => $purchase->items->pluck('product.name')->join(', '),
                'Quantities' => $purchase->items->pluck('quantity')->join(', '),
                'Prices' => $purchase->items->pluck('price')->map(fn ($p) => 'Rp' . number_format($p, 0, ',', '.'))->join(', '),
                'Total' => $purchase->items->sum(fn ($i) => $i->price * $i->quantity),
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Supplier', 'Items', 'Quantities', 'Prices', 'Total'];
    }
}
