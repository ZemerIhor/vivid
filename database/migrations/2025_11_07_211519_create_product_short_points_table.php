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
        Schema::create('product_short_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('lunar_products')->onDelete('cascade');
            $table->json('name'); // {"en": "...", "pl": "..."}
            $table->json('value'); // {"en": "...", "pl": "..."}
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_short_points');
    }
};
