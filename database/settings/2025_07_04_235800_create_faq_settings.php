<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Добавляем переводимый массив блоков FAQ с начальным значением
        $this->migrator->add('faq.faq_blocks', ['en' => [], 'pl' => []]);
    }

    public function down(): void
    {
        $this->migrator->delete('faq.faq_blocks');
    }
};
