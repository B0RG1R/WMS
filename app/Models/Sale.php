<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StockMovement;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'sale_date',
        'total_amount',
        'customer_id', // hanya pakai customer_id
        'customer_name',
        'total_amount',
    ];

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function salesItems()
    {
        return $this->hasMany(\App\Models\SalesItem::class);
    }



    public function finalizeStockMovement()
    {
        info('🔥 finalizeStockMovement DIPANGGIL SALE ID: ' . $this->id);
        $this->loadMissing('items.product');

        if ($this->items->isEmpty()) return;

        $total = 0;

        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock -= $item->quantity;
                $product->save();
            }

            StockMovement::create([
                'product_id' => $item->product_id,
                'movement_date' => now(),
                'movement_type' => 'out',
                'quantity' => $item->quantity,
                'reference_number' => $this->invoice_number ?? 'SALE-' . $this->id,
                'note' => 'Generated from Sale',
            ]);

            $total += $item->quantity * $item->price;
        }

        $this->updateQuietly(['total_amount' => $total]);
    }
}
