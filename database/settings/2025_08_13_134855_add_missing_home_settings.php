<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddMissingHomeSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('home.faq_main_image', null);
        $this->migrator->add('home.faq_main_image_alt', '');
    }

    public function down(): void
    {
        $this->migrator->delete('home.faq_main_image');
        $this->migrator->delete('home.faq_main_image_alt');
    }
}
