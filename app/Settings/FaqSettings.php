<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class FaqSettings extends Settings
{
    use HasTranslations;

    public array $faq_blocks; // Массив блоков FAQ (переводимый)

    protected array $translatable = [
        'faq_blocks', // Переводимый массив
    ];

    public static function group(): string
    {
        return 'faq';
    }
}
