<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('header.instagram_url', ['en' => '', 'uk' => '']);
        $this->migrator->add('header.facebook_url', ['en' => '', 'uk' => '']);
        $this->migrator->add('header.telegram_url', ['en' => '', 'uk' => '']);
    }

    public function down(): void
    {
        $this->migrator->delete('header.instagram_url');
        $this->migrator->delete('header.facebook_url');
        $this->migrator->delete('header.telegram_url');
    }
};
