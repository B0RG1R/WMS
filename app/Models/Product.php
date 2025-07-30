<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'minimum_stock',
        'category_id',
        'description',
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }


    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                do {
                    $sku = 'SKU-' . strtoupper(Str::random(6));
                } while (self::where('sku', $sku)->exists());

                $product->sku = $sku;
            }
        });
    }
}
