<?php

use App\Settings\HomeSettings;
use Illuminate\Database\Migrations\Migration;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateHomeSettings extends SettingsMigration
{
    public function up(): void
    {


        $this->migrator->add('home.hero_heading', '');
        $this->migrator->add('home.hero_slides', []);
        $this->migrator->add('home.hero_subheading', '');
        $this->migrator->add('home.hero_background_image', null);

        $this->migrator->add('home.advantages_cards', []);
        $this->migrator->add('home.advantages_image_1', null);
        $this->migrator->add('home.advantages_image_2', null);
        $this->migrator->add('home.advantages_image_3', null);

        $this->migrator->add('home.comparison_title', '');
        $this->migrator->add('home.main_comparison_image', null);
        $this->migrator->add('home.main_comparison_alt', '');
        $this->migrator->add('home.comparison_items', []);
        $this->migrator->add('home.central_text_value', '');
        $this->migrator->add('home.central_text_unit', '');
        $this->migrator->add('home.comparison_link', '');

        $this->migrator->add('home.faq_items', []);

        $this->migrator->add('home.feedback_form_title', '');
        $this->migrator->add('home.feedback_form_description', '');
        $this->migrator->add('home.feedback_form_image', null);
        $this->migrator->add('home.feedback_form_image_alt', '');

        $this->migrator->add('home.tenders_title', '');
        $this->migrator->add('home.tender_items', []);
        $this->migrator->add('home.tenders_phone', '');

        $this->migrator->add('home.about_title', '');
        $this->migrator->add('home.about_description', '');
        $this->migrator->add('home.about_more_link', '');
        $this->migrator->add('home.about_certificates_link', '');
        $this->migrator->add('home.about_statistic_title', '');
        $this->migrator->add('home.about_statistic_description', '');
        $this->migrator->add('home.about_location_image', null);
        $this->migrator->add('home.about_location_caption', '');

        $this->migrator->add('home.reviews_title', '');
        $this->migrator->add('home.review_items', []);
        $this->migrator->add('home.reviews_more_link', '');
    }

    public function down(): void
    {
        $this->migrator->delete('home.hero_slides', []);

        $this->migrator->delete('home.hero_heading');
        $this->migrator->delete('home.hero_subheading');
        $this->migrator->delete('home.hero_background_image');
        $this->migrator->delete('home.advantages_cards');
        $this->migrator->delete('home.advantages_image_1');
        $this->migrator->delete('home.advantages_image_2');
        $this->migrator->delete('home.advantages_image_3');

        $this->migrator->delete('home.comparison_title');
        $this->migrator->delete('home.main_comparison_image');
        $this->migrator->delete('home.main_comparison_alt');
        $this->migrator->delete('home.comparison_items');
        $this->migrator->delete('home.central_text_value');
        $this->migrator->delete('home.central_text_unit');
        $this->migrator->delete('home.comparison_link');

        $this->migrator->delete('home.faq_items');

        $this->migrator->delete('home.feedback_form_title');
        $this->migrator->delete('home.feedback_form_description');
        $this->migrator->delete('home.feedback_form_image');
        $this->migrator->delete('home.feedback_form_image_alt');

        $this->migrator->delete('home.tenders_title');
        $this->migrator->delete('home.tender_items');
        $this->migrator->delete('home.tenders_phone');

        $this->migrator->delete('home.about_title');
        $this->migrator->delete('home.about_description');
        $this->migrator->delete('home.about_more_link');
        $this->migrator->delete('home.about_certificates_link');
        $this->migrator->delete('home.about_statistic_title');
        $this->migrator->delete('home.about_statistic_description');
        $this->migrator->delete('home.about_location_image');
        $this->migrator->delete('home.about_location_caption');

        $this->migrator->delete('home.reviews_title');
        $this->migrator->delete('home.review_items');
        $this->migrator->delete('home.reviews_more_link');
    }
}
