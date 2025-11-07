<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Lunar\Models\Product as LunarProduct;
use Lunar\FieldTypes\TranslatedText;
use Illuminate\Support\Str;

class Product extends LunarProduct
{
    use HasFactory;

    public function localizedUrl(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        $locale = app()->getLocale();
        $languageId = \Lunar\Models\Language::where('code', $locale)->first()->id ?? 1;

        return $this->morphOne(\Lunar\Models\Url::class, 'element')
            ->where('language_id', $languageId);
    }

    public function defaultUrl(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(\Lunar\Models\Url::class, 'element')
            ->where('default', true);
    }

    public function getSlugAttribute(): string
    {
        if ($this->localizedUrl && $this->localizedUrl->slug) {
            return $this->localizedUrl->slug;
        }

        if ($this->defaultUrl && $this->defaultUrl->slug) {
            return $this->defaultUrl->slug;
        }

        $locale = app()->getLocale();
        $name = $this->attribute_data['name'] ?? null;

        if ($name instanceof TranslatedText && isset($name->value[$locale])) {
            $slug = Str::slug($name->value[$locale]);
            if ($slug) {
                return $slug;
            }
        }

        return 'product-' . $this->id;
    }

    /**
     * Get the characteristics for the product.
     */
    public function characteristics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCharacteristic::class)->orderBy('sort_order');
    }

    /**
     * Get the short points for the product.
     */
    public function shortPoints(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductShortPoint::class)->orderBy('sort_order');
    }
}
