<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Создаем таблицу видов торфа
        Schema::create('peat_types', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // {"en": "Agricultural Peat", "pl": "Torf rolniczy"}
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Создаем таблицу весов продуктов (фильтр)
        Schema::create('product_weights', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // {"en": "10 kg", "pl": "10 kg"}
            $table->string('value'); // 10, 20, 25, bulk
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Добавляем поля в таблицу продуктов
        Schema::table('lunar_products', function (Blueprint $table) {
            $table->foreignId('peat_type_id')->nullable()->after('brand_id')->constrained('peat_types')->nullOnDelete();
            $table->foreignId('product_weight_id')->nullable()->after('peat_type_id')->constrained('product_weights')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lunar_products', function (Blueprint $table) {
            $table->dropForeign(['peat_type_id']);
            $table->dropForeign(['product_weight_id']);
            $table->dropColumn(['peat_type_id', 'product_weight_id']);
        });

        Schema::dropIfExists('product_weights');
        Schema::dropIfExists('peat_types');
    }
};
