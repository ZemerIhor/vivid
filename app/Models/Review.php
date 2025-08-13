<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Review extends Model
{
    use HasTranslations;

    protected $guarded = ['id']; // Разрешаем массовое заполнение всех полей, кроме id

    public $translatable = ['comment']; // Поле comment будет переводимым

    protected $casts = [
        'published' => 'boolean', // Поле published как булево
        'published_at' => 'datetime', // Поле published_at как дата/время
        'rating' => 'integer', // Поле rating как целое число
    ];
}
