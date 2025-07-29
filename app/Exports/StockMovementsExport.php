<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockMovementsExport implements FromCollection, WithHeadings
{
    protected $movementType;

    public function __construct($movementType = null)
    {
        $this->movementType = $movementType;
    }

    public function collection()
    {
        $query = StockMovement::with('product');

        if ($this->movementType) {
            $query->where('movement_type', $this->movementType);
        }

        return $query->get()->map(function ($movement) {
            return [
                'product_name' => $movement->product->name ?? '-',
                'movement_date' => $movement->movement_date,
                'movement_type' => $movement->movement_type,
                'quantity' => $movement->quantity,
                'reference_number' => $movement->reference_number,
                'note' => $movement->note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Movement Date',
            'Type',
            'Quantity',
            'Reference Number',
            'Note',
        ];
    }
}

