<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     */
    public function run(): void
    {
        // Base data for collections, attributes, and taxes
        $this->call(CollectionSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(TaxSeeder::class);

        // Seed products with variants, prices, media and attach to collections
        $this->call(ProductSeeder::class);

        // Optional: additional product attributes in the 'details' group
        $this->call(ProductAttributesSeeder::class);

        // You can enable the following when needed
        // $this->call(CustomerSeeder::class);
        // $this->call(ShippingSeeder::class);
        // $this->call(OrderSeeder::class);
    }
}
