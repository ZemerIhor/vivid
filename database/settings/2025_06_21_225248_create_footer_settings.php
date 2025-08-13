<?php
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('footer.phone', '');
        $this->migrator->add('footer.email', '');
        $this->migrator->add('footer.address', '');
        $this->migrator->add('footer.copyright_text', '');
    }

    public function down(): void
    {
        $this->migrator->delete('footer.phone');
        $this->migrator->delete('footer.email');
        $this->migrator->delete('footer.address');
        $this->migrator->delete('footer.copyright_text');
    }
};
