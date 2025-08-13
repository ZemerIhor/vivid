<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contacts.map_latitude', 50.2397); // Пример широты для Смиги, Ровенская область
        $this->migrator->add('contacts.map_longitude', 25.7667); // Пример долготы для Смиги
    }

    public function down(): void
    {
        $this->migrator->delete('contacts.map_latitude');
        $this->migrator->delete('contacts.map_longitude');
    }
};
