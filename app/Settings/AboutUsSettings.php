<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class AboutUsSettings extends Settings
{
    use HasTranslations;

    public  $hero_background_image;
    public $hero_background_image_alt;
    public $hero_logo;
    public  $hero_logo_alt;
    public $hero_title;
    public $hero_subtitle;
    public $hero_subtitle_highlight;
    public $hero_slogan;
    public $hero_description;
    public $advantages;
    public  $advantage_images;
    public $about_title;
    public $about_description;
    public $gallery_title;
    public $gallery_images;
    public $certificates_title;
    public $certificates_images;

    protected $translatable = [
        'hero_background_image_alt',
        'hero_logo_alt',
        'hero_title',
        'hero_subtitle',
        'hero_subtitle_highlight',
        'hero_slogan',
        'hero_description',
        'advantages',
        'advantage_images',
        'about_title',
        'about_description',
        'gallery_title',
        'certificates_title',
        'certificates_images',
    ];

    public static function group(): string
    {
        return 'about_us';
    }
}
