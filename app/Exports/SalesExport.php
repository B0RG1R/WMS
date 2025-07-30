<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sale::with(['customer', 'items.product'])->get()->map(function ($sale) {
            return [
                'Invoice' => $sale->invoice_number,
                'Customer' => $sale->customer->name ?? '-',
                'Tanggal' => \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y'),
                'Produk' => $sale->items->pluck('product.name')->join(', '),
                'Qty' => $sale->items->pluck('quantity')->join(', '),
                'Harga' => $sale->items->pluck('price')->map(fn($p) => 'Rp' . number_format($p, 0, ',', '.'))->join(', '),
                'Total' => $sale->total_amount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Customer',
            'Tanggal',
            'Produk',
            'Qty',
            'Harga',
            'Total',
        ];
    }
}
