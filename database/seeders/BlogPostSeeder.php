<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('Starting to seed blog posts...');

        $blogPosts = [
            [
                'title' => [
                    'en' => 'The Future of E-commerce: Trends to Watch in 2025',
                    'pl' => 'Przyszłość e-commerce: trendy na 2025 rok',
                ],
                'slug' => 'future-of-ecommerce-trends-2025',
                'excerpt' => [
                    'en' => 'Discover the latest trends shaping the e-commerce industry and how they will impact online businesses in 2025.',
                    'pl' => 'Poznaj najnowsze trendy kształtujące branżę e-commerce i jak wpłyną na biznes online w 2025 roku.',
                ],
                'content' => [
                    'en' => '<h2>Introduction</h2><p>The e-commerce landscape is constantly evolving, and 2025 promises to bring exciting new developments. From AI-powered personalization to sustainable shopping practices, businesses need to stay ahead of the curve.</p><h2>Key Trends</h2><p><strong>1. AI and Machine Learning Integration</strong></p><p>Artificial intelligence will continue to revolutionize how customers shop online, with smarter recommendations and personalized experiences.</p><p><strong>2. Sustainability Focus</strong></p><p>Consumers are increasingly conscious about environmental impact, driving demand for eco-friendly products and sustainable packaging.</p><p><strong>3. Mobile Commerce Growth</strong></p><p>Mobile shopping will dominate, with improved mobile experiences and faster checkout processes.</p>',
                    'pl' => '<h2>Wprowadzenie</h2><p>Krajobraz e-commerce stale się rozwija, a 2025 rok obiecuje przynieść ekscytujące nowe rozwiązania. Od personalizacji opartej na sztucznej inteligencji po zrównoważone praktyki zakupowe, firmy muszą być o krok przed konkurencją.</p><h2>Kluczowe trendy</h2><p><strong>1. Integracja AI i uczenia maszynowego</strong></p><p>Sztuczna inteligencja będzie nadal rewolucjonizować sposób, w jaki klienci robią zakupy online, oferując inteligentniejsze rekomendacje i spersonalizowane doświadczenia.</p><p><strong>2. Fokus na zrównoważoność</strong></p><p>Konsumenci są coraz bardziej świadomi wpływu na środowisko, co napędza popyt na produkty przyjazne dla środowiska i zrównoważone opakowania.</p>',
                ],
                'seo_title' => [
                    'en' => 'E-commerce Trends 2025 | Future of Online Shopping',
                    'pl' => 'Trendy e-commerce 2025 | Przyszłość zakupów online',
                ],
                'seo_description' => [
                    'en' => 'Explore the top e-commerce trends for 2025 including AI integration, sustainability, and mobile commerce growth.',
                    'pl' => 'Poznaj najważniejsze trendy e-commerce na 2025 rok, w tym integrację AI, zrównoważoność i rozwój handlu mobilnego.',
                ],
                'published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => [
                    'en' => 'How to Choose the Perfect Product for Your Needs',
                    'pl' => 'Jak wybrać idealny produkt dla swoich potrzeb',
                ],
                'slug' => 'how-to-choose-perfect-product',
                'excerpt' => [
                    'en' => 'A comprehensive guide to help you make informed purchasing decisions and find products that truly meet your requirements.',
                    'pl' => 'Kompleksowy przewodnik, który pomoże Ci podejmować świadome decyzje zakupowe i znajdować produkty spełniające Twoje wymagania.',
                ],
                'content' => [
                    'en' => '<h2>Research Before You Buy</h2><p>Before making any purchase, it\'s essential to research thoroughly. Read reviews, compare features, and understand your specific needs.</p><h2>Consider Your Budget</h2><p>Set a realistic budget and stick to it. Remember that the most expensive option isn\'t always the best for your specific situation.</p><h2>Check Return Policies</h2><p>Always review the return policy before purchasing. This gives you peace of mind and protection if the product doesn\'t meet your expectations.</p>',
                    'pl' => '<h2>Zbadaj przed zakupem</h2><p>Przed dokonaniem jakiegokolwiek zakupu niezbędne jest dokładne zbadanie tematu. Przeczytaj recenzje, porównaj funkcje i zrozum swoje konkretne potrzeby.</p><h2>Rozważ swój budżet</h2><p>Ustaw realistyczny budżet i trzymaj się go. Pamiętaj, że najdroższa opcja nie zawsze jest najlepsza dla Twojej konkretnej sytuacji.</p><h2>Sprawdź politykę zwrotów</h2><p>Zawsze przejrzyj politykę zwrotów przed zakupem. Daje to spokój ducha i ochronę, jeśli produkt nie spełni Twoich oczekiwań.</p>',
                ],
                'seo_title' => [
                    'en' => 'Product Selection Guide | How to Choose Wisely',
                    'pl' => 'Przewodnik wyboru produktu | Jak mądrze wybierać',
                ],
                'seo_description' => [
                    'en' => 'Learn how to choose the perfect product with our comprehensive buying guide covering research, budget, and return policies.',
                    'pl' => 'Dowiedz się, jak wybrać idealny produkt dzięki naszemu kompleksowemu przewodnikowi zakupowemu.',
                ],
                'published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => [
                    'en' => 'Customer Service Excellence: What We Do Differently',
                    'pl' => 'Doskonałość obsługi klienta: co robimy inaczej',
                ],
                'slug' => 'customer-service-excellence',
                'excerpt' => [
                    'en' => 'Discover our commitment to exceptional customer service and what sets us apart from the competition.',
                    'pl' => 'Poznaj nasze zaangażowanie w wyjątkową obsługę klienta i to, co wyróżnia nas na tle konkurencji.',
                ],
                'content' => [
                    'en' => '<h2>Our Philosophy</h2><p>Customer satisfaction is at the heart of everything we do. We believe that excellent service creates lasting relationships and trust.</p><h2>24/7 Support</h2><p>Our customer support team is available around the clock to assist with any questions or concerns you may have.</p><h2>Quick Response Times</h2><p>We pride ourselves on responding to customer inquiries within 1 hour during business hours and within 4 hours outside of business hours.</p>',
                    'pl' => '<h2>Nasza filozofia</h2><p>Zadowolenie klienta jest sercem wszystkiego, co robimy. Wierzymy, że doskonała obsługa tworzy trwałe relacje i zaufanie.</p><h2>Wsparcie 24/7</h2><p>Nasz zespół obsługi klienta jest dostępny przez całą dobę, aby pomóc w przypadku jakichkolwiek pytań lub problemów.</p><h2>Szybkie czasy odpowiedzi</h2><p>Szczycimy się odpowiadaniem na zapytania klientów w ciągu 1 godziny w godzinach pracy i w ciągu 4 godzin poza godzinami pracy.</p>',
                ],
                'seo_title' => [
                    'en' => 'Exceptional Customer Service | Our Commitment',
                    'pl' => 'Wyjątkowa obsługa klienta | Nasze zobowiązanie',
                ],
                'seo_description' => [
                    'en' => 'Learn about our customer service philosophy and 24/7 support commitment that sets us apart.',
                    'pl' => 'Poznaj naszą filozofię obsługi klienta i zobowiązanie do wsparcia 24/7, które nas wyróżnia.',
                ],
                'published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => [
                    'en' => 'Seasonal Shopping Guide: Best Deals Throughout the Year',
                    'pl' => 'Przewodnik zakupów sezonowych: najlepsze oferty przez cały rok',
                ],
                'slug' => 'seasonal-shopping-guide-best-deals',
                'excerpt' => [
                    'en' => 'Maximize your savings with our comprehensive guide to the best shopping seasons and deals throughout the year.',
                    'pl' => 'Zmaksymalizuj swoje oszczędności dzięki naszemu kompleksowemu przewodnikowi po najlepszych sezonach zakupowych przez cały rok.',
                ],
                'content' => [
                    'en' => '<h2>Spring Sales</h2><p>Spring is perfect for home renovation and gardening supplies. Many retailers offer clearance sales on winter items.</p><h2>Summer Deals</h2><p>Look for outdoor gear, vacation essentials, and back-to-school items. Electronics often go on sale during summer months.</p><h2>Fall Shopping</h2><p>Fall brings excellent deals on clothing, appliances, and holiday preparation items.</p><h2>Winter Bargains</h2><p>The holiday season and post-holiday clearances offer some of the year\'s best deals across all categories.</p>',
                    'pl' => '<h2>Wiosenne wyprzedaże</h2><p>Wiosna jest idealna na artykuły do renowacji domu i ogrodnictwa. Wielu sprzedawców oferuje wyprzedaże artykułów zimowych.</p><h2>Letnie okazje</h2><p>Szukaj sprzętu outdoor, artykułów wakacyjnych i rzeczy szkolnych. Elektronika często jest w promocji w miesiącach letnich.</p><h2>Jesienne zakupy</h2><p>Jesień przynosi doskonałe okazje na odzież, sprzęt AGD i artykuły do przygotowań świątecznych.</p>',
                ],
                'seo_title' => [
                    'en' => 'Seasonal Shopping Guide | Year-Round Deals & Savings',
                    'pl' => 'Przewodnik zakupów sezonowych | Okazje i oszczędności',
                ],
                'seo_description' => [
                    'en' => 'Discover the best times to shop for maximum savings with our seasonal shopping guide covering all year round.',
                    'pl' => 'Odkryj najlepsze momenty na zakupy dla maksymalnych oszczędności z naszym przewodnikiem sezonowym.',
                ],
                'published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => [
                    'en' => 'Technology Integration in Modern Retail',
                    'pl' => 'Integracja technologii w nowoczesnym handlu detalicznym',
                ],
                'slug' => 'technology-integration-modern-retail',
                'excerpt' => [
                    'en' => 'Explore how cutting-edge technology is transforming the retail experience and what it means for consumers.',
                    'pl' => 'Poznaj, jak nowoczesna technologia przekształca doświadczenie zakupowe i co to oznacza dla konsumentów.',
                ],
                'content' => [
                    'en' => '<h2>Virtual Reality Shopping</h2><p>VR technology allows customers to experience products virtually before purchasing, revolutionizing online shopping.</p><h2>Augmented Reality Try-Ons</h2><p>AR enables customers to try products virtually, from clothing to furniture, reducing returns and increasing satisfaction.</p><h2>AI-Powered Recommendations</h2><p>Machine learning algorithms analyze customer behavior to provide personalized product recommendations.</p>',
                    'pl' => '<h2>Zakupy w rzeczywistości wirtualnej</h2><p>Technologia VR pozwala klientom doświadczyć produktów wirtualnie przed zakupem, rewolucjonizując zakupy online.</p><h2>Przymiarki w rzeczywistości rozszerzonej</h2><p>AR umożliwia klientom wirtualne przymierzanie produktów, od odzieży po meble, redukując zwroty i zwiększając satysfakcję.</p>',
                ],
                'seo_title' => [
                    'en' => 'Retail Technology Trends | VR, AR & AI Integration',
                    'pl' => 'Trendy technologiczne w handlu | Integracja VR, AR i AI',
                ],
                'seo_description' => [
                    'en' => 'Discover how VR, AR, and AI technologies are transforming modern retail and enhancing customer experience.',
                    'pl' => 'Odkryj, jak technologie VR, AR i AI przekształcają nowoczesny handel detaliczny.',
                ],
                'published' => false, // Unpublished for testing
                'published_at' => null,
            ],
        ];

        foreach ($blogPosts as $postData) {
            BlogPost::create($postData);
        }

        Log::info('Finished seeding blog posts. Created ' . count($blogPosts) . ' blog posts.');
    }
}
