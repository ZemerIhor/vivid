<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class HomeSettings extends Settings
{
    use HasTranslations;

    public  $banner_image = null;
    public $banner_title;
    public $banner_description;

    public $hero_slides;
    public $advantages_cards;
    public  $advantages_image_1 = null;
    public  $advantages_image_2 = null;
    public  $advantages_image_3 = null;
    public $comparison_title;
    public  $main_comparison_image = null;
    public $main_comparison_alt;
    public  $faq_main_image = null;
    public $faq_main_image_alt;
    public $comparison_items;
    public $central_text_value;
    public $central_text_unit;
    public $faq_items;
    public $feedback_form_title;
    public $feedback_form_description;
    public  $feedback_form_image = null;
    public $feedback_form_image_alt;
    public $tenders_title;
    public $tender_items;
    public $tenders_phone;
    public $about_title;
    public $about_description;
    public $about_more_link;
    public $about_certificates_link;
    public $about_statistic_title;
    public $about_statistic_description;
    public  $about_location_image = null;
    public $about_location_caption;

    public static function group(): string
    {
        return 'home';
    }
}
