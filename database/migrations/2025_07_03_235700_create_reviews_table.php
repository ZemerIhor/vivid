<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Имя автора отзыва
            $table->integer('rating')->unsigned()->between(1, 5); // Рейтинг (1-5)
            $table->json('comment'); // Текст отзыва (переводимый)
            $table->boolean('published')->default(false); // Статус публикации
            $table->timestamp('published_at')->nullable(); // Дата публикации
            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
