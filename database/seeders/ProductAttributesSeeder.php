<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Lunar\Models\Product;
use Lunar\FieldTypes\Text;

class ProductAttributesSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Найти существующую группу с handle 'details' и type 'product'
            $group = AttributeGroup::where('handle', 'details')
                ->first();

            if (!$group) {
                throw new \Exception("Attribute group 'details' not found for products.");
            }

            // Атрибуты
            $attributes = [
                'calories' => ['en' => 'Calories', 'uk' => 'Калорійність'],
                'moisture' => ['en' => 'Moisture content', 'uk' => 'Масова доля загальної вологи'],
                'strength' => ['en' => 'Mechanical strength', 'uk' => 'Механічна міцність'],
                'ash' => ['en' => 'Ash content', 'uk' => 'Зольність'],
                'dimensions' => ['en' => 'Dimensions', 'uk' => 'Розміри'],
                'material' => ['en' => 'Material', 'uk' => 'Сировина'],
                'packaging' => ['en' => 'Packaging type', 'uk' => 'Вид пакування'],
            ];

            $position = $group->attributes()->count() + 1;

            foreach ($attributes as $handle => $translations) {
                Attribute::firstOrCreate([
                    'handle' => $handle,
                    'attribute_type' => 'product',
                ], [
                    'attribute_group_id' => $group->id,
                    'name' => $translations,
                    'type' => TranslatedText::class, // Изменено на TranslatedText
                    'position' => $position++,
                    'section' => 'main',
                    'searchable' => true,
                    'filterable' => false,
                    'system' => false,
                    'description' => $translations,
                    'configuration' => [],
                ]);
            }
        });
    }
}
