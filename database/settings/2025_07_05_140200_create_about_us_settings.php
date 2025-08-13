<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Hero Section
        $this->migrator->add('about_us.hero_background_image', '');
        $this->migrator->add('about_us.hero_background_image_alt', ['en' => 'About Us Hero Background', 'uk' => 'Фонове зображення сторінки Про нас']);
        $this->migrator->add('about_us.hero_logo', '');
        $this->migrator->add('about_us.hero_logo_alt', ['en' => 'Company Logo', 'uk' => 'Логотип компанії']);
        $this->migrator->add('about_us.hero_title', ['en' => 'About LAND GROU', 'uk' => 'Про LAND GROU']);
        $this->migrator->add('about_us.hero_subtitle', ['en' => 'Ukrainian company specializing in peat extraction and processing', 'uk' => 'Українська компанія з видобування й переробки торфу']);
        $this->migrator->add('about_us.hero_subtitle_highlight', ['en' => 'peat', 'uk' => 'торфу']);
        $this->migrator->add('about_us.hero_slogan', ['en' => 'Keep warm', 'uk' => 'Зберігайте тепло']);
        $this->migrator->add('about_us.hero_description', ['en' => 'LLC "Land Grou" is a leading Ukrainian enterprise in peat extraction and processing, located in Rivne Oblast. We are the successor to the region’s peat industry history, which began in 1953. Our company plays a key role in ensuring Ukraine’s energy security by offering high-quality peat briquettes as an alternative to traditional fuels.', 'uk' => 'ТОВ «Ленд Гроу» — провідне українське підприємство з видобування та переробки торфу, розташоване в Рівненській області. Ми є правонаступником багаторічної історії торф’яної промисловості регіону, що бере свій початок з 1953 року. Наше підприємство відіграє ключову роль у забезпеченні енергетичної безпеки України, пропонуючи високоякісні торф’яні брикети як альтернативу традиційним видам палива.']);

        // Advantages Section
        $this->migrator->add('about_us.advantages', [
            [
                'value' => 180,
                'title' => ['en' => 'Thousand Tons', 'uk' => 'Тисяч тонн'],
                'description' => ['en' => 'Peat per year', 'uk' => 'Торфу на рік'],
            ],
            [
                'value' => 20,
                'title' => ['en' => 'Years', 'uk' => 'Років'],
                'description' => ['en' => 'Team experience', 'uk' => 'Досвіду роботи колективу'],
            ],
            [
                'value' => 75,
                'title' => ['en' => 'Thousand Tons', 'uk' => 'Тисяч тонн'],
                'description' => ['en' => 'Peat briquettes per year', 'uk' => 'Торфових брикетів на рік'],
            ],
        ]);
        $this->migrator->add('about_us.advantage_images', [
            ['image' => '', 'alt' => ['en' => 'Peat Briquettes', 'uk' => 'Торфові брикети']],
            ['image' => '', 'alt' => ['en' => 'Peat Production', 'uk' => 'Виробництво торфу']],
            ['image' => '', 'alt' => ['en' => 'Peat Fields', 'uk' => 'Торфові поля']],
        ]);

        // About Section
        $this->migrator->add('about_us.about_background_images', [
            ['image' => '', 'alt' => ['en' => 'Background Image 1', 'uk' => 'Фонове зображення 1']],
            ['image' => '', 'alt' => ['en' => 'Background Image 2', 'uk' => 'Фонове зображення 2']],
        ]);
        $this->migrator->add('about_us.about_title', ['en' => 'About Us', 'uk' => 'Про нас']);
        $this->migrator->add('about_us.about_description', [
            'en' => [
                'We extract from 100 to 180 thousand tons of peat annually, primarily for fuel purposes, producing up to 90 thousand tons of peat briquettes. Our products meet DSTU 2042-92 standards, characterized by high calorific value, low ash content, and no chemical additives, ensuring efficient and eco-friendly heat for various consumers.',
                'Our team consists of highly qualified specialists, many with over 20 years of experience in the peat industry. We continuously implement modern technologies and innovative solutions to maintain high standards of quality and production efficiency.',
                'We actively collaborate with various economic sectors, including private consumers, agricultural enterprises, industrial complexes, and public institutions. Our products are widely used in greenhouses, poultry farms, brick and cement factories, and municipal boiler houses, contributing to reduced energy costs and increased energy efficiency.',
                'LLC "Land Grou" is also actively developing its export direction, supplying milled peat and peat briquettes to international partners. We aim to expand our supply geography and strengthen our position in the global market, offering reliable and eco-friendly fuel solutions.',
                'We are proud of our history, experience, and contribution to Ukraine’s energy independence. Our mission is to provide consumers with high-quality, affordable, and environmentally safe fuel, promoting sustainable development and environmental preservation.',
            ],
            'uk' => [
                'Ми щорічно видобуваємо від 100 до 180 тисяч тонн торфу, переважно паливного призначення, з якого виготовляємо до 90 тисяч тонн торфових брикетів. Наша продукція відповідає вимогам ДСТУ 2042-92 та характеризується високою калорійністю, низькою зольністю та відсутністю хімічних добавок. Завдяки цьому, наші брикети забезпечують ефективне та екологічно чисте тепло для різноманітних споживачів.',
                'Наша команда складається з висококваліфікованих фахівців, багато з яких мають понад 20 років досвіду в торф’яній галузі. Ми постійно впроваджуємо сучасні технології та інноваційні рішення, що дозволяє нам підтримувати високі стандарти якості та ефективності виробництва.',
                'Ми активно співпрацюємо з різними секторами економіки, включаючи приватних споживачів, аграрні підприємства, промислові комплекси та бюджетні установи. Наша продукція широко використовується в тепличних господарствах, птахофабриках, цегельних та цементних заводах, а також у комунальних котельнях, сприяючи зниженню витрат на енергоресурси та підвищенню енергоефективності.',
                'ТОВ «Ленд Гроу» також активно розвиває експортний напрямок, постачаючи фрезерний торф та торф’яні брикети зарубіжним партнерам. Ми прагнемо до розширення географії поставок та зміцнення позицій на міжнародному ринку, пропонуючи надійні та екологічно чисті паливні рішення.',
                'Ми пишаємося своєю історією, досвідом та внеском у розвиток енергетичної незалежності України. Наша місія — забезпечити споживачів якісним, доступним та екологічно безпечним паливом, сприяючи сталому розвитку та збереженню навколишнього середовища.',
            ],
        ]);
        $this->migrator->add('about_us.catalog_button_icon', '');
        $this->migrator->add('about_us.buy_button_icon', '');

        // Gallery Section
        $this->migrator->add('about_us.gallery_title', ['en' => 'Gallery', 'uk' => 'Галерея']);
        $this->migrator->add('about_us.gallery_images', [
            ['image' => '', 'alt' => ['en' => 'Peat Fields and Production', 'uk' => 'Торфові поля та виробництво']],
            ['image' => '', 'alt' => ['en' => 'Peat Extraction Process', 'uk' => 'Процес видобування торфу']],
            ['image' => '', 'alt' => ['en' => 'Peat Briquettes', 'uk' => 'Торфові брикети']],
            ['image' => '', 'alt' => ['en' => 'Product Storage', 'uk' => 'Складування продукції']],
            ['image' => '', 'alt' => ['en' => 'Production Equipment', 'uk' => 'Обладнання для виробництва']],
        ]);

        // Certificates Section
        $this->migrator->add('about_us.certificates_title', ['en' => 'Quality Certificates', 'uk' => 'Сертифікати якості']);
        $this->migrator->add('about_us.certificates_images', [
            ['image' => '', 'alt' => ['en' => 'Quality Certificate 1', 'uk' => 'Сертифікат якості 1']],
            ['image' => '', 'alt' => ['en' => 'Quality Certificate 2', 'uk' => 'Сертифікат якості 2']],
            ['image' => '', 'alt' => ['en' => 'Quality Certificate 3', 'uk' => 'Сертифікат якості 3']],
            ['image' => '', 'alt' => ['en' => 'Quality Certificate 4', 'uk' => 'Сертифікат якості 4']],
            ['image' => '', 'alt' => ['en' => 'Quality Certificate 5', 'uk' => 'Сертифікат якості 5']],
        ]);
    }

    public function down(): void
    {
        $this->migrator->delete('about_us.hero_background_image');
        $this->migrator->delete('about_us.hero_background_image_alt');
        $this->migrator->delete('about_us.hero_logo');
        $this->migrator->delete('about_us.hero_logo_alt');
        $this->migrator->delete('about_us.hero_title');
        $this->migrator->delete('about_us.hero_subtitle');
        $this->migrator->delete('about_us.hero_subtitle_highlight');
        $this->migrator->delete('about_us.hero_slogan');
        $this->migrator->delete('about_us.hero_description');
        $this->migrator->delete('about_us.advantages');
        $this->migrator->delete('about_us.advantage_images');
        $this->migrator->delete('about_us.about_background_images');
        $this->migrator->delete('about_us.about_title');
        $this->migrator->delete('about_us.about_description');
        $this->migrator->delete('about_us.catalog_button_icon');
        $this->migrator->delete('about_us.buy_button_icon');
        $this->migrator->delete('about_us.gallery_title');
        $this->migrator->delete('about_us.gallery_images');
        $this->migrator->delete('about_us.certificates_title');
        $this->migrator->delete('about_us.certificates_images');
    }
};
