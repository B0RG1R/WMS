<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{

    public function product()
    {   
        return $this->belongsTo(Product::class);
    }

    protected $fillable = [
        'entry_date',
        'product_id',
        'quantity',
        'reference_number',
        'note',
    ];

    protected static function booted()
    {
        static::created(function ($entry) {
            \App\Models\StockMovement::create([
                'product_id'       => $entry->product_id,
                'movement_date'    => $entry->entry_date,
                'movement_type'    => 'in',
                'quantity'         => $entry->quantity,
                'reference_number' => $entry->reference_number,
                'note'             => $entry->note,
            ]);
        });
    }

}
