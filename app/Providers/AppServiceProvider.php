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
use Illuminate\Support\Facades\App;
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
                        ->addLocation('header_en', 'Header EN') // Location for header (English)
                        ->addLocation('header_pl', 'Header PL') // Location for header (Polish)
                        ->addLocation('footer_en', 'Footer EN') // Location for footer (English)
                        ->addLocation('footer_pl', 'Footer PL') // Location for footer (Polish)
                        ->showCustomLinkPanel(true)
                        ->addMenuPanels([
                            // Header for English locale
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
                            // Header for Polish locale
                            StaticMenuPanel::make('header_pl')
                                ->addMany([
                                    'Strona główna' => url('/pl'),
                                    'Katalog' => url('/pl/catalog'),
                                    'Blog' => url('/pl/blog'),
                                    'FAQ' => url('/pl/faq'),
                                    'Opinie' => url('/pl/reviews'),
                                    'O nas' => url('/pl/about-us'),
                                    'Kontakt' => url('/pl/contacts'),
                                ]),
                            // Footer for English locale
                            StaticMenuPanel::make('footer_en')
                                ->addMany([
                                    'FAQ' => url('/en/faq'),
                                    'Privacy Policy' => url('/en/privacy-policy'),
                                    'Terms of Use' => url('/en/terms'),
                                    'Contacts' => url('/en/contacts'),
                                    'Reviews' => url('/en/reviews'),
                                ]),
                            // Footer for Polish locale
                            StaticMenuPanel::make('footer_pl')
                                ->addMany([
                                    'FAQ' => url('/pl/faq'),
                                    'Polityka prywatności' => url('/pl/privacy-policy'),
                                    'Warunki użytkowania' => url('/pl/terms'),
                                    'Kontakt' => url('/pl/contacts'),
                                    'Opinie' => url('/pl/reviews'),
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

        // Register Observers
        \App\Models\Review::observe(\App\Observers\ReviewObserver::class);
        \App\Models\BlogPost::observe(\App\Observers\BlogPostObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
    }
}
