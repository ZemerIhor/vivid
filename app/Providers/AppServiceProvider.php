<?php

namespace App\Providers;

use App\Filament\Pages\AboutUs;
use App\Filament\Pages\Contacts;
use App\Filament\Pages\Faq;
use App\Filament\Pages\Footer;
use App\Filament\Pages\GlobalSet;
use App\Filament\Pages\Header;
use App\Filament\Pages\Home;
use App\Filament\Resources\BlogPostResource;
use App\Filament\Resources\ReviewResource;
use App\Livewire\Components\Breadcrumbs;
use App\Livewire\Elements\PromoBoxElement;
use App\Modifiers\ShippingModifier;
use App\Pipelines\Order\Creation\CustomFillOrderFromCart;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel;
use Filament\SpatieLaravelTranslatablePlugin;
use Geosem42\Filamentor\FilamentorPlugin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Kenepa\TranslationManager\TranslationManagerPlugin;
use Lunar\Admin\Filament\Resources\ProductResource;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Base\ShippingModifiers;
use Lunar\Shipping\ShippingPlugin;
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(
            fn($panel) => $panel
                ->pages([
                    Footer::class,
                    Home::class,
                    Header::class,
                    Faq::class,
                    Contacts::class,
                    AboutUs::class,
                    GlobalSet::class,
                ])
                ->resources([
                    BlogPostResource::class,
                    ReviewResource::class,
                    ProductResource::class,
                ])
                ->plugins([
                    new ShippingPlugin,
                    FilamentMenuBuilderPlugin::make()
                        ->addLocation('header_en', 'Header EN') // Локация для хедера (английский)
                        ->addLocation('header_uk', 'Header UK') // Локация для хедера (украинский)
                        ->addLocation('footer_en', 'Footer EN') // Локация для футера (английский)
                        ->addLocation('footer_uk', 'Footer UK') // Локация для футера (украинский)
                        ->showCustomLinkPanel(true)
                        ->addMenuPanels([
                            // Хедер для английской локали
                            StaticMenuPanel::make('header_en')
                                ->addMany([
                                    'Home' => url('/en'),
                                    'Catalog' => url('/en/catalog'),
                                    'Blog' => url('/en/blog'),
                                    'FAQ' => url('/en/faq'),
                                    'Reviews' => url('/en/reviews'),
                                    'About Us' => url('/en/about-us'),
                                    'Contacts' => url('/en/contacts'),
                                ]),
                            // Хедер для украинской локали
                            StaticMenuPanel::make('header_uk')
                                ->addMany([
                                    'Головна' => url('/uk'),
                                    'Каталог' => url('/pl/catalog'),
                                    'Блог' => url('/pl/blog'),
                                    'FAQ' => url('/pl/faq'),
                                    'Відгуки' => url('/pl/reviews'),
                                    'Про нас' => url('/pl/about-us'),
                                    'Контакти' => url('/pl/contacts'),
                                ]),
                            // Футер для английской локали
                            StaticMenuPanel::make('footer_en')
                                ->addMany([
                                    'FAQ' => url('/en/faq'),
                                    'Privacy Policy' => url('/en/privacy-policy'),
                                    'Terms of Use' => url('/en/terms'),
                                    'Contacts' => url('/en/contacts'),
                                    'Reviews' => url('/en/reviews'),
                                ]),
                            // Футер для украинской локали
                            StaticMenuPanel::make('footer_uk')
                                ->addMany([
                                    'FAQ' => url('/pl/faq'),
                                    'Політика конфіденційності' => url('/pl/privacy-policy'),
                                    'Умови використання' => url('/pl/terms'),
                                    'Контакти' => url('/pl/contacts'),
                                    'Відгуки' => url('/pl/reviews'),
                                ]),
                        ]),
                    FilamentorPlugin::make(),
                    FilamentTranslateFieldPlugin::make()
                        ->defaultLocales(['en', 'pl']),
                ])
        )->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShippingModifiers $shippingModifiers): void
    {
        $shippingModifiers->add(
            ShippingModifier::class
        );
        \Lunar\Facades\ModelManifest::replace(
            \Lunar\Models\Contracts\Product::class,
            \App\Models\Product::class,
        );
    }
}
