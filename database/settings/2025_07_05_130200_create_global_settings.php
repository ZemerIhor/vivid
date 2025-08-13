<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('global.site_name', ['en' => 'My Website', 'pl' => 'Мій Вебсайт']);
        $this->migrator->add('global.meta_description', ['en' => 'Welcome to our website', 'pl' => 'Ласкаво просимо на наш вебсайт']);
        $this->migrator->add('global.logo', '');
        $this->migrator->add('global.favicon', '');
        $this->migrator->add('global.contact_email', 'contact@example.com');
        $this->migrator->add('global.feedback_form_title', ['en' => 'Feedback Form', 'pl' => 'Форма зворотного зв’язку']);
        $this->migrator->add('global.feedback_form_description', ['en' => 'Please share your feedback with us', 'pl' => 'Будь ласка, поділіться вашими відгуками']);
        $this->migrator->add('global.feedback_form_image', '');
    }

    public function down(): void
    {
        $this->migrator->delete('global.site_name');
        $this->migrator->delete('global.meta_description');
        $this->migrator->delete('global.logo');
        $this->migrator->delete('global.favicon');
        $this->migrator->delete('global.contact_email');
        $this->migrator->delete('global.feedback_form_title');
        $this->migrator->delete('global.feedback_form_description');
        $this->migrator->delete('global.feedback_form_image');
    }
};
