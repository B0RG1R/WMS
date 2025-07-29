<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivingItem extends Model
{
    protected $fillable = [
        'purchase_item_id',
        'receiving_id',
        'product_name',
        'received_qty',
    ];

    public function receiving(): BelongsTo
    {
        return $this->belongsTo(Receiving::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
