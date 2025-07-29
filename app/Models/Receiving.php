<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receiving extends Model
{
    protected $fillable = [
        'purchase_id',
        'received_date',
        'received_by',
        'notes',
    ];


    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function receivingItems(): HasMany
    {
        return $this->hasMany(ReceivingItem::class);
    }
}
