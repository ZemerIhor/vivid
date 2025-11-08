<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeatType extends Model
{
    protected $table = 'peat_types';

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(\Lunar\Models\Product::class, 'peat_type_id');
    }

    public function translate(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->name[$locale] ?? $this->name['en'] ?? '';
    }
}
