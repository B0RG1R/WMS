<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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

    protected static function booted()
    {
        static::created(function ($item) {
            // Kurangi stok produk
            $product = $item->product;
            if ($product && $item->quantity > 0 && $product->stock >= $item->quantity) {
                $product->decrement('stock', $item->quantity);
            }

            // Update total_amount di Sale
            $sale = $item->sale;
            if ($sale) {
                $total = $sale->items()->sum(DB::raw('quantity * price'));
                $sale->updateQuietly(['total_amount' => $total]);
            }
        });
    }
}
