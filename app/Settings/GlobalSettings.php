<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalSettings extends Settings
{
    public  $site_name;
    public  $meta_description;
    public  $logo;
    public  $favicon;
    public  $contact_email;
    public  $feedback_form_title;
    public  $feedback_form_description;
    public  $feedback_form_image;

    // Поля для статических страниц
    public  $home_title;
    public  $home_meta_description;
    public  $about_us_title;
    public  $about_us_meta_description;
    public  $contacts_title;
    public  $contacts_meta_description;
    public  $faq_title;
    public  $faq_meta_description;
    public  $reviews_title;
    public  $reviews_meta_description;
    public  $submit_review_title;
    public  $submit_review_meta_description;
    public  $blog_title;
    public  $blog_meta_description;
    public  $checkout_title;
    public  $checkout_meta_description;
    public  $checkout_success_title;
    public  $checkout_success_meta_description;

    public static function group():string
    {
        return 'global';
    }
}
