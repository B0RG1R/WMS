<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Tambahkan ini untuk mengurangi stok saat item dibuat
    protected static function booted()
    {
        static::created(function ($item) {
            // Update stock
            $product = \App\Models\Product::find($item->product_id);
            if ($product && $product->stock >= $item->quantity) {
                $product->stock -= $item->quantity;
                $product->save();
            }
            // Update total amount sale
            $sale = $item->sale;
            $total = $sale->items()->sum(\DB::raw('quantity * price'));
            $sale->total_amount = $total;
            $sale->save();
        });
    }
}
