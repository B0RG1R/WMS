<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'movement_date',
        'movement_type',
        'quantity',
        'reference_number',
        'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
