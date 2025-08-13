<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;


return new class extends SettingsMigration

{
    public function up(): void
    {
        $this->migrator->add('home.banner_image', null);
        $this->migrator->add('home.banner_title', ['en' => '', 'uk' => '']);
        $this->migrator->add('home.banner_description', ['en' => '', 'uk' => '']);
    }

    public function down(): void
    {
        $this->migrator->delete('home.banner_image');
        $this->migrator->delete('home.banner_title');
        $this->migrator->delete('home.banner_description');
    }
};
