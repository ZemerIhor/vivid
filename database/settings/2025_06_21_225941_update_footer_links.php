<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('footer.social_links', []);
        $this->migrator->add('footer.sections', []);
    }

    public function down(): void
    {
        $this->migrator->delete('footer.social_links');
        $this->migrator->delete('footer.sections');
    }
};
