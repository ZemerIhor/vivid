<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class HeaderSettings extends Settings
{
    use HasTranslations;

    public $instagram_url;
    public $facebook_url;
    public $telegram_url;

    protected array $translatable = [
        'instagram_url',
        'facebook_url',
        'telegram_url',
    ];

    public static function group(): string
    {
        return 'header';
    }
}
