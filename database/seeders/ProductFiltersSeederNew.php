<?php

namespace Database\Seeders;

use App\Models\PeatType;
use App\Models\ProductWeight;
use Illuminate\Database\Seeder;

class ProductFiltersSeederNew extends Seeder
{
    public function run(): void
    {
        // Создаем виды торфа
        $types = [
            [
                'name' => ['en' => 'Agricultural Peat', 'pl' => 'Torf rolniczy'],
                'slug' => 'agricultural-peat',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Substrate Peat', 'pl' => 'Torf podłożowy'],
                'slug' => 'substrate-peat',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'High-Moor Peat', 'pl' => 'Torf wysokoprądowy'],
                'slug' => 'high-moor-peat',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            PeatType::updateOrCreate(['slug' => $type['slug']], $type);
        }

        // Создаем веса продуктов
        $weights = [
            ['name' => ['en' => '10 kg', 'pl' => '10 kg'], 'value' => '10', 'sort_order' => 1, 'is_active' => true],
            ['name' => ['en' => '20 kg', 'pl' => '20 kg'], 'value' => '20', 'sort_order' => 2, 'is_active' => true],
            ['name' => ['en' => '25 kg', 'pl' => '25 kg'], 'value' => '25', 'sort_order' => 3, 'is_active' => true],
            ['name' => ['en' => 'Bulk', 'pl' => 'Luzem'], 'value' => 'bulk', 'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($weights as $weight) {
            ProductWeight::updateOrCreate(['value' => $weight['value']], $weight);
        }

        $this->command->info('✅ Peat types and product weights seeded successfully!');
    }
}
