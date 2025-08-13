<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Добавляем новые поля для статических страниц
        $this->migrator->add('global.home_title', ['en' => 'Home Page', 'pl' => 'Головна сторінка']);
        $this->migrator->add('global.home_meta_description', ['en' => 'Welcome to our site’s home page', 'pl' => 'Ласкаво просимо на головну сторінку нашого сайту']);
        $this->migrator->add('global.about_us_title', ['en' => 'About Us', 'pl' => 'Про нас']);
        $this->migrator->add('global.about_us_meta_description', ['en' => 'Learn more about our company', 'pl' => 'Дізнайтесь більше про нашу компанію']);
        $this->migrator->add('global.contacts_title', ['en' => 'Contacts', 'pl' => 'Контакти']);
        $this->migrator->add('global.contacts_meta_description', ['en' => 'Get in touch with us', 'pl' => 'Зв’яжіться з нами']);
        $this->migrator->add('global.faq_title', ['en' => 'FAQ', 'pl' => 'Поширені запитання']);
        $this->migrator->add('global.faq_meta_description', ['en' => 'Answers to frequently asked questions', 'pl' => 'Відповіді на поширені запитання']);
        $this->migrator->add('global.reviews_title', ['en' => 'Reviews', 'pl' => 'Відгуки']);
        $this->migrator->add('global.reviews_meta_description', ['en' => 'Read our customer reviews', 'pl' => 'Читайте відгуки наших клієнтів']);
        $this->migrator->add('global.submit_review_title', ['en' => 'Submit Review', 'pl' => 'Залишити відгук']);
        $this->migrator->add('global.submit_review_meta_description', ['en' => 'Share your feedback about our products', 'pl' => 'Поділіться своїм відгуком про наші продукти']);
        $this->migrator->add('global.blog_title', ['en' => 'Blog', 'pl' => 'Блог']);
        $this->migrator->add('global.blog_meta_description', ['en' => 'Read our latest articles and news', 'pl' => 'Читайте наші останні статті та новини']);
        $this->migrator->add('global.checkout_title', ['en' => 'Checkout', 'pl' => 'Оформлення замовлення']);
        $this->migrator->add('global.checkout_meta_description', ['en' => 'Complete your order quickly and easily', 'pl' => 'Оформіть ваше замовлення швидко та зручно']);
        $this->migrator->add('global.checkout_success_title', ['en' => 'Order Successfully Placed', 'pl' => 'Замовлення успішно оформлено']);
        $this->migrator->add('global.checkout_success_meta_description', ['en' => 'Thank you for your order!', 'pl' => 'Дякуємо за ваше замовлення!']);
    }

    public function down(): void
    {
        // Удаляем добавленные поля
        $this->migrator->delete('global.home_title');
        $this->migrator->delete('global.home_meta_description');
        $this->migrator->delete('global.about_us_title');
        $this->migrator->delete('global.about_us_meta_description');
        $this->migrator->delete('global.contacts_title');
        $this->migrator->delete('global.contacts_meta_description');
        $this->migrator->delete('global.faq_title');
        $this->migrator->delete('global.faq_meta_description');
        $this->migrator->delete('global.reviews_title');
        $this->migrator->delete('global.reviews_meta_description');
        $this->migrator->delete('global.submit_review_title');
        $this->migrator->delete('global.submit_review_meta_description');
        $this->migrator->delete('global.blog_title');
        $this->migrator->delete('global.blog_meta_description');
        $this->migrator->delete('global.checkout_title');
        $this->migrator->delete('global.checkout_meta_description');
        $this->migrator->delete('global.checkout_success_title');
        $this->migrator->delete('global.checkout_success_meta_description');
    }
};
