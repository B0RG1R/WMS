<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StockMovement;



class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'purchase_date',
        'supplier_id',
        'total_amount',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum(fn ($item) => $item->quantity * $item->price);
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }

    public function receivings(): HasMany
    {
        return $this->hasMany(Receiving::class);
    }



    public function finalizeStockMovement()
    {
        $this->loadMissing('items.product');
        $total = 0;

        foreach ($this->items as $item) {
            $product = $item->product;

            if ($product) {
                $product->stock += $item->quantity;
                $product->save();
            }

            $total += $item->quantity * $item->price;

            StockMovement::create([
                'product_id' => $item->product_id,
                'movement_date' => now(),
                'movement_type' => 'in',
                'quantity' => $item->quantity,
                'reference_number' => $this->invoice_number ?? 'PURCHASE-' . $this->id,
                'note' => 'Generated from Purchase',
            ]);
        }

        $this->updateQuietly(['total_amount' => $total]);
    }

}

