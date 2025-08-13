<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('Starting to seed reviews...');

        $reviews = [
            [
                'name' => 'Анна Коваленко',
                'rating' => 5,
                'comment' => [
                    'en' => 'Excellent product! Very satisfied with the quality and fast delivery. Will definitely order again.',
                    'pl' => 'Doskonały produkt! Bardzo zadowolona z jakości i szybkiej dostawy. Na pewno zamówię ponownie.',
                ],
                'published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'name' => 'Петр Смирнов',
                'rating' => 4,
                'comment' => [
                    'en' => 'Good quality, but delivery took a bit longer than expected. Overall happy with the purchase.',
                    'pl' => 'Dobra jakość, ale dostawa trwała trochę dłużej niż oczekiwano. Ogólnie zadowolony z zakupu.',
                ],
                'published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'name' => 'Мария Иванова',
                'rating' => 5,
                'comment' => [
                    'en' => 'Perfect! Exactly what I was looking for. Great customer service and packaging.',
                    'pl' => 'Idealny! Dokładnie to, czego szukałam. Świetna obsługa klienta i opakowanie.',
                ],
                'published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'name' => 'Александр Попов',
                'rating' => 3,
                'comment' => [
                    'en' => 'Average product. It does what it should, but nothing exceptional. Price is fair.',
                    'pl' => 'Przeciętny produkt. Robi to, co powinien, ale nic wyjątkowego. Cena jest uczciwa.',
                ],
                'published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'name' => 'Елена Васильева',
                'rating' => 5,
                'comment' => [
                    'en' => 'Amazing quality and design! This exceeded my expectations. Highly recommend to everyone.',
                    'pl' => 'Niesamowita jakość i design! To przewyższyło moje oczekiwania. Gorąco polecam wszystkim.',
                ],
                'published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'name' => 'Дмитрий Орлов',
                'rating' => 4,
                'comment' => [
                    'en' => 'Very good product for the price. Minor issues with packaging, but the item itself is great.',
                    'pl' => 'Bardzo dobry produkt za tę cenę. Drobne problemy z opakowaniem, ale sam przedmiot jest świetny.',
                ],
                'published' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'name' => 'Ольга Сидорова',
                'rating' => 5,
                'comment' => [
                    'en' => 'Outstanding! Fast shipping, excellent quality, and great customer support. Will buy again!',
                    'pl' => 'Wybitny! Szybka wysyłka, doskonała jakość i świetne wsparcie klienta. Kupię ponownie!',
                ],
                'published' => false, // Unpublished review for testing
                'published_at' => null,
            ],
            [
                'name' => 'Игорь Федоров',
                'rating' => 2,
                'comment' => [
                    'en' => 'Not satisfied with the quality. The product arrived damaged and customer service was slow to respond.',
                    'pl' => 'Niezadowolony z jakości. Produkt przyszedł uszkodzony, a obsługa klienta powoli odpowiadała.',
                ],
                'published' => false, // Unpublished negative review
                'published_at' => null,
            ],
            [
                'name' => 'Виктория Морозова',
                'rating' => 4,
                'comment' => [
                    'en' => 'Good value for money. The product works as described. Delivery was on time.',
                    'pl' => 'Dobra relacja jakości do ceny. Produkt działa zgodnie z opisem. Dostawa była punktualna.',
                ],
                'published' => true,
                'published_at' => now()->subDays(6),
            ],
            [
                'name' => 'Андрей Козлов',
                'rating' => 5,
                'comment' => [
                    'en' => 'Fantastic! This is my third order from this store. Consistently high quality and service.',
                    'pl' => 'Fantastyczny! To moje trzecie zamówienie w tym sklepie. Stale wysoka jakość i obsługa.',
                ],
                'published' => true,
                'published_at' => now()->subHours(12),
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create($reviewData);
        }

        Log::info('Finished seeding reviews. Created ' . count($reviews) . ' reviews.');
    }
}
