<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contacts.main_address', [
            'pl' => '35680, вулиця Миру, будинок 1, cмт. Смига, Дубенський район, Рівненська область',
            'en' => '35680, Myru Street, Building 1, Smyha, Dubno District, Rivne Region',
        ]);
        $this->migrator->add('contacts.main_email', 'office@landgrou.com');
        $this->migrator->add('contacts.sales_phones', ['+380935258877', '+380935258877', '+380935258877']);
        $this->migrator->add('contacts.sales_email', 'sales@landgrou.com');
        $this->migrator->add('contacts.export_phone', '+48731303479');
        $this->migrator->add('contacts.export_contact', [
            'pl' => 'Вадим Каневський',
            'en' => 'Wadym Kaniewski',
        ]);
        $this->migrator->add('contacts.export_email', 'w.kaniewski@landgrou.com');
        $this->migrator->add('contacts.additional_emails', [
            'office' => 'office@landgrou.com',
            'sales' => 'sales@landgrou.com',
            'accounting' => 'buh@landgrou.com',
            'supply' => 'provision@landgrou.com',
            'lawyer' => 'lawyer@landgrou.com',
            'project_manager' => 'a.shulga@landgrou.com',
        ]);
        $this->migrator->add('contacts.map_image', '');
        $this->migrator->add('contacts.map_image_alt', [
            'pl' => 'Карта розташування офісу',
            'en' => 'Office Location Map',
        ]);
    }

    public function down(): void
    {
        $this->migrator->delete('contacts.main_address');
        $this->migrator->delete('contacts.main_email');
        $this->migrator->delete('contacts.sales_phones');
        $this->migrator->delete('contacts.sales_email');
        $this->migrator->delete('contacts.export_phone');
        $this->migrator->delete('contacts.export_contact');
        $this->migrator->delete('contacts.export_email');
        $this->migrator->delete('contacts.additional_emails');
        $this->migrator->delete('contacts.map_image');
        $this->migrator->delete('contacts.map_image_alt');
    }
};
