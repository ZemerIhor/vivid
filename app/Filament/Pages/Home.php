<?php

namespace App\Filament\Pages;

use App\Settings\HomeSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use Illuminate\Support\Facades\Log;

class Home extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.home';
    protected static string $settings = HomeSettings::class;
    protected static ?string $navigationLabel = 'Home Settings';

    public static function getSlug(): string
    {
        return 'home';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(HomeSettings::class);

        $this->data = [
            'banner_image' => $settings->banner_image,
            'banner_title' => $settings->banner_title ?? ['en' => '', 'uk' => ''],
            'banner_description' => $settings->banner_description ?? ['en' => '', 'uk' => ''],
            'hero_slides' => $settings->hero_slides ?? ['en' => [], 'uk' => []],
            'advantages_cards' => $settings->advantages_cards ?? ['en' => [], 'uk' => []],
            'advantages_image_1' => $settings->advantages_image_1,
            'advantages_image_2' => $settings->advantages_image_2,
            'advantages_image_3' => $settings->advantages_image_3,
            'comparison_title' => $settings->comparison_title ?? ['en' => '', 'uk' => ''],
            'main_comparison_image' => $settings->main_comparison_image,
            'main_comparison_alt' => $settings->main_comparison_alt ?? ['en' => '', 'uk' => ''],
            'comparison_items' => $settings->comparison_items ?? ['en' => [], 'uk' => []],
            'central_text_value' => $settings->central_text_value ?? ['en' => '', 'uk' => ''],
            'central_text_unit' => $settings->central_text_unit ?? ['en' => '', 'uk' => ''],
            'faq_items' => $settings->faq_items ?? ['en' => [], 'uk' => []],
            'faq_main_image' => $settings->faq_main_image ?? null,
            'faq_main_image_alt' => $settings->faq_main_image_alt ?? ['en' => '', 'uk' => ''],
            'feedback_form_title' => $settings->feedback_form_title ?? ['en' => '', 'uk' => ''],
            'feedback_form_description' => $settings->feedback_form_description ?? ['en' => '', 'uk' => ''],
            'feedback_form_image' => $settings->feedback_form_image,
            'feedback_form_image_alt' => $settings->feedback_form_image_alt ?? ['en' => '', 'uk' => ''],
            'tenders_title' => $settings->tenders_title ?? ['en' => '', 'uk' => ''],
            'tender_items' => $settings->tender_items ?? ['en' => [], 'uk' => []],
            'tenders_phone' => $settings->tenders_phone ?? ['en' => '', 'uk' => ''],
            'about_title' => $settings->about_title ?? ['en' => '', 'uk' => ''],
            'about_description' => $settings->about_description ?? ['en' => '', 'uk' => ''],
            'about_more_link' => $settings->about_more_link ?? ['en' => '', 'uk' => ''],
            'about_certificates_link' => $settings->about_certificates_link ?? ['en' => '', 'uk' => ''],
            'about_statistic_title' => $settings->about_statistic_title ?? ['en' => '', 'uk' => ''],
            'about_statistic_description' => $settings->about_statistic_description ?? ['en' => '', 'uk' => ''],
            'about_location_image' => $settings->about_location_image,
            'about_location_caption' => $settings->about_location_caption ?? ['en' => '', 'uk' => ''],
            'reviews_title' => $settings->reviews_title ?? ['en' => '', 'uk' => ''],
            'review_items' => $settings->review_items ?? ['en' => [], 'uk' => []],
        ];

        Log::info('Home Settings Loaded Data', ['data' => $this->data]);
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Главный баннер'))
                    ->schema([
                        FileUpload::make('banner_image')
                            ->label(__('Изображение баннера'))
                            ->directory('home/main_banner')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                TextInput::make('banner_title')
                                    ->label(__('Заголовок баннера'))
                                    ->maxLength(255),
                                Textarea::make('banner_description')
                                    ->label(__('Описание баннера'))
                                    ->maxLength(500),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('Баннер'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                Repeater::make('hero_slides')
                                    ->label(__('Слайды баннера'))
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label(__('Заголовок'))
                                            ->maxLength(255),
                                        Textarea::make('subheading')
                                            ->label(__('Подзаголовок'))
                                            ->maxLength(500),
                                        TextInput::make('extra_text')
                                            ->label(__('Дополнительный текст'))
                                            ->maxLength(255),
                                        FileUpload::make('background_image')
                                            ->label(__('Фоновое изображение'))
                                            ->directory('home/banners')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image(),
                                    ])
                                    ->maxItems(5)
                                    ->collapsible()
                                    ->reorderable()
                                    ->cloneable(),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('Преимущества'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                Repeater::make('advantages_cards')
                                    ->label(__('Карточки преимуществ'))
                                    ->schema([
                                        FileUpload::make('icon')
                                            ->label(__('Иконка'))
                                            ->directory('home/advantages/icons')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image(),
                                        TextInput::make('title')
                                            ->label(__('Заголовок'))
                                            ->maxLength(100),
                                        Textarea::make('description')
                                            ->label(__('Описание'))
                                            ->maxLength(500),
                                    ])
                                    ->maxItems(4)
                                    ->collapsible()
                                    ->reorderable()
                                    ->cloneable(),
                            ]),
                        FileUpload::make('advantages_image_1')
                            ->label(__('Изображение 1'))
                            ->directory('home/advantages')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                        FileUpload::make('advantages_image_2')
                            ->label(__('Изображение 2'))
                            ->directory('home/advantages')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                        FileUpload::make('advantages_image_3')
                            ->label(__('Изображение 3'))
                            ->directory('home/advantages')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                    ])
                    ->collapsible(),

                Section::make(__('Сравнение'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                TextInput::make('comparison_title')
                                    ->label(__('Заголовок'))
                                    ->maxLength(255),
                                TextInput::make('main_comparison_alt')
                                    ->label(__('Альтернативный текст основного изображения'))
                                    ->maxLength(255),
                                Repeater::make('comparison_items')
                                    ->label(__('Пункты сравнения'))
                                    ->schema([
                                        TextInput::make('value')
                                            ->label(__('Значение'))
                                            ->maxLength(50),
                                        TextInput::make('unit')
                                            ->label(__('Единица измерения'))
                                            ->maxLength(100),
                                        TextInput::make('alt')
                                            ->label(__('Альтернативный текст изображения'))
                                            ->maxLength(255),
                                        FileUpload::make('image')
                                            ->label(__('Изображение'))
                                            ->directory('home/comparison/items')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image(),
                                    ])
                                    ->maxItems(3)
                                    ->collapsible()
                                    ->reorderable()
                                    ->cloneable(),
                                TextInput::make('central_text_value')
                                    ->label(__('Центральное значение'))
                                    ->maxLength(50),
                                TextInput::make('central_text_unit')
                                    ->label(__('Центральная единица'))
                                    ->maxLength(100),
                            ]),
                        FileUpload::make('main_comparison_image')
                            ->label(__('Основное изображение'))
                            ->directory('home/comparison')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                    ])
                    ->collapsible(),

                Section::make(__('Поширені запитання'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                Repeater::make('faq_items')
                                    ->label(__('Пункти FAQ'))
                                    ->schema([
                                        TextInput::make('question')
                                            ->label(__('Питання'))
                                            ->maxLength(255),
                                        Textarea::make('answer')
                                            ->label(__('Відповідь'))
                                            ->maxLength(500),
                                        FileUpload::make('icon')
                                            ->label(__('Иконка'))
                                            ->directory('home/faq/icons')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image(),
                                    ])
                                    ->maxItems(10)
                                    ->collapsible()
                                    ->reorderable()
                                    ->cloneable(),
                                TextInput::make('faq_main_image_alt')
                                    ->label(__('Альтернативный текст основного изображения FAQ'))
                                    ->maxLength(255),
                            ]),
                        FileUpload::make('faq_main_image')
                            ->label(__('Основное изображение FAQ'))
                            ->directory('home/faq')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                    ])
                    ->collapsible(),

                Section::make(__('Тендери'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                TextInput::make('tenders_title')
                                    ->label(__('Заголовок'))
                                    ->maxLength(255),
                                Repeater::make('tender_items')
                                    ->label(__('Пункти тендерів'))
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('Назва'))
                                            ->maxLength(255)
                                            ->required(),
                                        FileUpload::make('icon')
                                            ->label(__('Иконка категории'))
                                            ->directory('home/tenders/icons')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image(),
                                        TextInput::make('background_color')
                                            ->label(__('Колір фону (HEX, наприклад #34C759)'))
                                            ->maxLength(7)
                                            ->default('#34C759')
                                            ->regex('/^#[0-9A-F]{6}$/i'),
                                    ])
                                    ->maxItems(5)
                                    ->collapsible()
                                    ->reorderable()
                                    ->cloneable(),
                                TextInput::make('tenders_phone')
                                    ->label(__('Телефон відділу тендерів'))
                                    ->maxLength(20),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('Про нас'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'uk'])
                            ->schema([
                                TextInput::make('about_title')
                                    ->label(__('Заголовок'))
                                    ->maxLength(255),
                                Textarea::make('about_description')
                                    ->label(__('Опис'))
                                    ->maxLength(1000),
                                TextInput::make('about_more_link')
                                    ->label(__('Посилання "Більше"'))
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('about_certificates_link')
                                    ->label(__('Посилання "Сертифікати"'))
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('about_statistic_title')
                                    ->label(__('Заголовок статистики'))
                                    ->maxLength(255),
                                Textarea::make('about_statistic_description')
                                    ->label(__('Опис статистики'))
                                    ->maxLength(1000),
                                TextInput::make('about_location_caption')
                                    ->label(__('Підпис до зображення локації'))
                                    ->maxLength(255),
                            ]),
                        FileUpload::make('about_location_image')
                            ->label(__('Зображення локації'))
                            ->directory('home/about')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->image(),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            Log::info('Home Settings Form Data', ['data' => $data]);

            $fileFields = [
                'banner_image',
                'advantages_image_1', 'advantages_image_2', 'advantages_image_3',
                'main_comparison_image', 'feedback_form_image', 'about_location_image',
                'faq_main_image',
            ];

            foreach ($fileFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = is_array($data[$field]) ? ($data[$field][0] ?? null) : $data[$field];
                }
            }

            $translatableArrays = [
                'hero_slides', 'advantages_cards', 'comparison_items', 'faq_items',
                'tender_items', 'review_items',
            ];

            foreach ($translatableArrays as $field) {
                if (!isset($data[$field]) || !is_array($data[$field])) {
                    $data[$field] = ['en' => [], 'uk' => []];
                } else {
                    foreach (['en', 'uk'] as $locale) {
                        if (!isset($data[$field][$locale]) || !is_array($data[$field][$locale])) {
                            $data[$field][$locale] = [];
                        }
                    }
                }
            }

            if (isset($data['hero_slides']) && is_array($data['hero_slides'])) {
                foreach ($data['hero_slides'] as $locale => &$slides) {
                    if (is_array($slides)) {
                        foreach ($slides as &$slide) {
                            if (isset($slide['background_image'])) {
                                $slide['background_image'] = is_array($slide['background_image']) ? ($slide['background_image'][0] ?? null) : $slide['background_image'];
                            }
                        }
                        unset($slide);
                    }
                }
                unset($slides);
            }

            if (isset($data['advantages_cards']) && is_array($data['advantages_cards'])) {
                foreach ($data['advantages_cards'] as $locale => &$cards) {
                    if (is_array($cards)) {
                        foreach ($cards as &$card) {
                            if (isset($card['icon'])) {
                                $card['icon'] = is_array($card['icon']) ? ($card['icon'][0] ?? null) : $card['icon'];
                            }
                        }
                        unset($card);
                    }
                }
                unset($cards);
            }

            if (isset($data['comparison_items']) && is_array($data['comparison_items'])) {
                foreach ($data['comparison_items'] as $locale => &$items) {
                    if (is_array($items)) {
                        foreach ($items as &$item) {
                            if (isset($item['image'])) {
                                $item['image'] = is_array($item['image']) ? ($item['image'][0] ?? null) : $item['image'];
                            }
                        }
                        unset($item);
                    }
                }
                unset($items);
            }

            if (isset($data['tender_items']) && is_array($data['tender_items'])) {
                foreach ($data['tender_items'] as $locale => &$items) {
                    if (is_array($items)) {
                        foreach ($items as &$item) {
                            if (isset($item['icon'])) {
                                $item['icon'] = is_array($item['icon']) ? ($item['icon'][0] ?? null) : $item['icon'];
                            }
                        }
                        unset($item);
                    }
                }
                unset($items);
            }

            if (isset($data['faq_items']) && is_array($data['faq_items'])) {
                foreach ($data['faq_items'] as $locale => &$items) {
                    if (is_array($items)) {
                        foreach ($items as &$item) {
                            if (isset($item['icon'])) {
                                $item['icon'] = is_array($item['icon']) ? ($item['icon'][0] ?? null) : $item['icon'];
                            }
                        }
                        unset($item);
                    }
                }
                unset($items);
            }

            $settings = app(HomeSettings::class);
            Log::info('Settings before save', ['settings' => $settings->toArray()]);

            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title(__('Дані головної сторінки збережено!'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('Error saving Home Settings', ['error' => $e->getMessage()]);
            Notification::make()
                ->title(__('Помилка збереження налаштувань'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
