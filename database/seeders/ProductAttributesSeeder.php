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
                'calories' => ['en' => 'Calories', 'pl' => 'Калорійність'],
                'moisture' => ['en' => 'Moisture content', 'pl' => 'Масова доля загальної вологи'],
                'strength' => ['en' => 'Mechanical strength', 'pl' => 'Механічна міцність'],
                'ash' => ['en' => 'Ash content', 'pl' => 'Зольність'],
                'dimensions' => ['en' => 'Dimensions', 'pl' => 'Розміри'],
                'material' => ['en' => 'Material', 'pl' => 'Сировина'],
                'packaging' => ['en' => 'Packaging type', 'pl' => 'Вид пакування'],
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
