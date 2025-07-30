<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('category')->get()->map(function ($product) {
            return [
                'Product Name'   => $product->name,
                'Category'       => $product->category->name ?? '-',
                'Stock'          => $product->stock,
                'Min. Stock'     => $product->minimum_stock,
                'Status'         => $product->stock <= 0 ? '❌ Out of Stock' : ($product->stock <= $product->minimum_stock ? '⚠️ Low' : '✅ OK'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Category',
            'Stock',
            'Min. Stock',
            'Status',
        ];
    }
}
