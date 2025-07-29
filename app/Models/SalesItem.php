<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
=======
>>>>>>> 82fdca1 (progres dashboard)

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

<<<<<<< HEAD
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
=======
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
>>>>>>> 82fdca1 (progres dashboard)
        });
    }
}
