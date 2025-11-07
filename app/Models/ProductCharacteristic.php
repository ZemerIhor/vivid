<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCharacteristic extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'standard',
        'actual',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'standard' => 'array',
        'actual' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
