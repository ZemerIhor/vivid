<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class ContactSettings extends Settings
{
    use HasTranslations;

    // Переводимые поля
    public  $main_address;
    public  $export_contact;
    public  $map_image_alt;

    // Непереводимые поля
    public  $main_email;
    public  $sales_phones;
    public  $sales_email;
    public  $export_phone;
    public  $export_email;
    public  $map_latitude;
    public  $map_longitude;
    public  $additional_emails;
    public ?string  $map_image;

    // Публичное свойство для переводимых полей
    protected array $translatable = [
        'main_address',
        'export_contact',
        'map_image_alt',
    ];

    public static function group(): string
    {
        return 'contacts';
    }

    // Добавляем метод для отладки

}
