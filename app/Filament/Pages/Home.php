<?php

namespace App\Filament\Pages;

use App\Settings\HomeSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
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
    protected static ?string $navigationLabel = 'Налаштування головної';

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
            'banner_title' => $settings->banner_title ?? ['en' => '', 'pl' => ''],
            'banner_description' => $settings->banner_description ?? ['en' => '', 'pl' => ''],
            'hero_slides' => $settings->hero_slides ?? ['en' => [], 'pl' => []],
            'advantages_cards' => $settings->advantages_cards ?? ['en' => [], 'pl' => []],
            'advantages_image_1' => $settings->advantages_image_1,
            'advantages_image_2' => $settings->advantages_image_2,
            'advantages_image_3' => $settings->advantages_image_3,
            'comparison_title' => $settings->comparison_title ?? ['en' => '', 'pl' => ''],
            'main_comparison_image' => $settings->main_comparison_image,
            'main_comparison_alt' => $settings->main_comparison_alt ?? ['en' => '', 'pl' => ''],
            'comparison_items' => $settings->comparison_items ?? ['en' => [], 'pl' => []],
            'central_text_value' => $settings->central_text_value ?? ['en' => '', 'pl' => ''],
            'central_text_unit' => $settings->central_text_unit ?? ['en' => '', 'pl' => ''],
            'faq_items' => $settings->faq_items ?? ['en' => [], 'pl' => []],
            'faq_main_image' => $settings->faq_main_image ?? null,
            'faq_main_image_alt' => $settings->faq_main_image_alt ?? ['en' => '', 'pl' => ''],
            'feedback_form_title' => $settings->feedback_form_title ?? ['en' => '', 'pl' => ''],
            'feedback_form_description' => $settings->feedback_form_description ?? ['en' => '', 'pl' => ''],
            'feedback_form_image' => $settings->feedback_form_image,
            'feedback_form_image_alt' => $settings->feedback_form_image_alt ?? ['en' => '', 'pl' => ''],
            'tenders_title' => $settings->tenders_title ?? ['en' => '', 'pl' => ''],
            'tender_items' => $settings->tender_items ?? ['en' => [], 'pl' => []],
            'tenders_phone' => $settings->tenders_phone ?? ['en' => '', 'pl' => ''],
            'about_title' => $settings->about_title ?? ['en' => '', 'pl' => ''],
            'about_description' => $settings->about_description ?? ['en' => '', 'pl' => ''],
            'about_more_link' => $settings->about_more_link ?? ['en' => '', 'pl' => ''],
            'about_certificates_link' => $settings->about_certificates_link ?? ['en' => '', 'pl' => ''],
            'about_statistic_title' => $settings->about_statistic_title ?? ['en' => '', 'pl' => ''],
            'about_statistic_description' => $settings->about_statistic_description ?? ['en' => '', 'pl' => ''],
            'about_location_image' => $settings->about_location_image,
            'about_location_caption' => $settings->about_location_caption ?? ['en' => '', 'pl' => ''],
            'reviews_title' => $settings->reviews_title ?? ['en' => '', 'pl' => ''],
            'review_items' => $settings->review_items ?? ['en' => [], 'pl' => []],
        ];

        Log::info('Home Settings Loaded Data', ['data' => $this->data]);
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('Главный баннер'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        FileUpload::make('banner_image')
                                            ->label(__('Изображение баннера'))
                                            ->directory('home/main_banner')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('banner_title')
                                                    ->label(__('Заголовок баннера'))
                                                    ->maxLength(255),
                                                Textarea::make('banner_description')
                                                    ->label(__('Описание баннера'))
                                                    ->maxLength(500),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Баннер'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
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
                                                            ->image()
                                                            ->nullable(),
                                                    ])
                                                    ->maxItems(5)
                                                    ->collapsible()
                                                    ->reorderable()
                                                    ->cloneable(),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Преимущества'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                Repeater::make('advantages_cards')
                                                    ->label(__('Карточки преимуществ'))
                                                    ->schema([
                                                        FileUpload::make('icon')
                                                            ->label(__('Иконка'))
                                                            ->directory('home/advantages')
                                                            ->disk('public')
                                                            ->preserveFilenames()
                                                            ->maxSize(5120)
                                                            ->image()
                                                            ->nullable(),
                                                        TextInput::make('title')
                                                            ->label(__('Заголовок'))
                                                            ->maxLength(255),
                                                        Textarea::make('description')
                                                            ->label(__('Описание'))
                                                            ->maxLength(500),
                                                    ])
                                                    ->collapsible()
                                                    ->reorderable()
                                                    ->cloneable(),
                                            ]),

                                        FileUpload::make('advantages_image_1')
                                            ->label(__('Изображение преимуществ 1'))
                                            ->directory('home/advantages')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),

                                        FileUpload::make('advantages_image_2')
                                            ->label(__('Изображение преимуществ 2'))
                                            ->directory('home/advantages')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),

                                        FileUpload::make('advantages_image_3')
                                            ->label(__('Изображение преимуществ 3'))
                                            ->directory('home/advantages')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Сравнение'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('comparison_title')
                                                    ->label(__('Заголовок сравнения'))
                                                    ->maxLength(255),
                                            ]),

                                        FileUpload::make('main_comparison_image')
                                            ->label(__('Главное изображение сравнения'))
                                            ->directory('home/comparison')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),

                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('main_comparison_alt')
                                                    ->label(__('Альтернативный текст'))
                                                    ->maxLength(255),
                                            ]),

                                        Repeater::make('comparison_items')
                                            ->label(__('Элементы сравнения'))
                                            ->schema([
                                                TextInput::make('label')
                                                    ->label(__('Метка'))
                                                    ->maxLength(255),
                                                TextInput::make('value')
                                                    ->label(__('Значение'))
                                                    ->maxLength(255),
                                            ])
                                            ->collapsible()
                                            ->reorderable()
                                            ->cloneable(),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Центральный текст'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('central_text_value')
                                                    ->label(__('Значение'))
                                                    ->maxLength(50),
                                                TextInput::make('central_text_unit')
                                                    ->label(__('Единица измерения'))
                                                    ->maxLength(50),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Вопрос-ответ'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('faq_items')
                                            ->label(__('Вопросы и ответы'))
                                            ->schema([
                                                TextInput::make('question')
                                                    ->label(__('Вопрос'))
                                                    ->maxLength(255),
                                                RichEditor::make('answer')
                                                    ->label(__('Ответ'))
                                                    ->maxLength(1000)
                                                    ->disableToolbarButtons([
                                                        'attachFiles',
                                                    ]),
                                            ])
                                            ->collapsible()
                                            ->reorderable()
                                            ->cloneable(),

                                        FileUpload::make('faq_main_image')
                                            ->label(__('Главное изображение'))
                                            ->directory('home/faq')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),

                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('faq_main_image_alt')
                                                    ->label(__('Альтернативный текст'))
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Форма обратной связи'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('feedback_form_title')
                                                    ->label(__('Заголовок формы'))
                                                    ->maxLength(255),
                                                Textarea::make('feedback_form_description')
                                                    ->label(__('Описание формы'))
                                                    ->maxLength(500),
                                            ]),

                                        FileUpload::make('feedback_form_image')
                                            ->label(__('Изображение формы'))
                                            ->directory('home/feedback')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),

                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('feedback_form_image_alt')
                                                    ->label(__('Альтернативный текст'))
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Тендеры'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('tenders_title')
                                                    ->label(__('Заголовок тендеров'))
                                                    ->maxLength(255),
                                            ]),

                                        Repeater::make('tender_items')
                                            ->label(__('Элементы тендеров'))
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label(__('Заголовок'))
                                                    ->maxLength(255),
                                                TextInput::make('number')
                                                    ->label(__('Номер'))
                                                    ->maxLength(50),
                                                TextInput::make('date')
                                                    ->label(__('Дата'))
                                                    ->maxLength(50),
                                                TextInput::make('status')
                                                    ->label(__('Статус'))
                                                    ->maxLength(50),
                                                FileUpload::make('file')
                                                    ->label(__('Файл'))
                                                    ->directory('home/tenders')
                                                    ->disk('public')
                                                    ->preserveFilenames()
                                                    ->maxSize(5120)
                                                    ->acceptedFileTypes(['application/pdf'])
                                                    ->nullable(),
                                            ])
                                            ->collapsible()
                                            ->reorderable()
                                            ->cloneable(),

                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('tenders_phone')
                                                    ->label(__('Телефон'))
                                                    ->maxLength(50),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('О нас'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('about_title')
                                                    ->label(__('Заголовок о нас'))
                                                    ->maxLength(255),
                                                Textarea::make('about_description')
                                                    ->label(__('Описание о нас'))
                                                    ->maxLength(1000),
                                                TextInput::make('about_more_link')
                                                    ->label(__('Ссылка "Подробнее"'))
                                                    ->maxLength(255),
                                                TextInput::make('about_certificates_link')
                                                    ->label(__('Ссылка на сертификаты'))
                                                    ->maxLength(255),
                                                TextInput::make('about_statistic_title')
                                                    ->label(__('Заголовок статистики'))
                                                    ->maxLength(255),
                                                Textarea::make('about_statistic_description')
                                                    ->label(__('Описание статистики'))
                                                    ->maxLength(500),
                                            ]),

                                        FileUpload::make('about_location_image')
                                            ->label(__('Изображение местоположения'))
                                            ->directory('home/about')
                                            ->disk('public')
                                            ->preserveFilenames()
                                            ->maxSize(5120)
                                            ->image()
                                            ->nullable(),

                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('about_location_caption')
                                                    ->label(__('Подпись к изображению'))
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Отзывы'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('reviews_title')
                                                    ->label(__('Заголовок отзывов'))
                                                    ->maxLength(255),
                                            ]),

                                        Repeater::make('review_items')
                                            ->label(__('Отзывы'))
                                            ->schema([
                                                TextInput::make('author')
                                                    ->label(__('Автор'))
                                                    ->maxLength(255),
                                                TextInput::make('position')
                                                    ->label(__('Должность'))
                                                    ->maxLength(255),
                                                Textarea::make('text')
                                                    ->label(__('Текст'))
                                                    ->maxLength(500),
                                                FileUpload::make('avatar')
                                                    ->label(__('Аватар'))
                                                    ->directory('home/reviews')
                                                    ->disk('public')
                                                    ->preserveFilenames()
                                                    ->maxSize(5120)
                                                    ->image()
                                                    ->nullable(),
                                            ])
                                            ->collapsible()
                                            ->reorderable()
                                            ->cloneable(),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            Log::info('Home Settings Form Data', ['data' => $data]);

            // Handle file fields (allow null values)
            $fileFields = [
                'banner_image',
                'advantages_image_1', 'advantages_image_2', 'advantages_image_3',
                'main_comparison_image', 'feedback_form_image', 'about_location_image',
                'faq_main_image',
            ];

            foreach ($fileFields as $field) {
                $data[$field] = isset($data[$field]) && is_array($data[$field]) ? ($data[$field][0] ?? null) : ($data[$field] ?? null);
            }

            // Handle translatable arrays (ensure they are arrays, even if empty)
            $translatableArrays = [
                'hero_slides', 'advantages_cards', 'comparison_items', 'faq_items',
                'tender_items', 'review_items',
            ];

            foreach ($translatableArrays as $field) {
                if (!isset($data[$field]) || !is_array($data[$field])) {
                    $data[$field] = ['en' => [], 'pl' => []];
                } else {
                    foreach (['en', 'pl'] as $locale) {
                        $data[$field][$locale] = isset($data[$field][$locale]) && is_array($data[$field][$locale]) ? $data[$field][$locale] : [];
                    }
                }
            }

            // Process hero_slides images
            if (isset($data['hero_slides']) && is_array($data['hero_slides'])) {
                foreach ($data['hero_slides'] as $locale => &$slides) {
                    if (is_array($slides)) {
                        foreach ($slides as &$slide) {
                            $slide['background_image'] = isset($slide['background_image']) && is_array($slide['background_image']) ? ($slide['background_image'][0] ?? null) : ($slide['background_image'] ?? null);
                        }
                        unset($slide);
                    }
                }
                unset($slides);
            }

            // Process advantages_cards icons
            if (isset($data['advantages_cards']) && is_array($data['advantages_cards'])) {
                foreach ($data['advantages_cards'] as $locale => &$cards) {
                    if (is_array($cards)) {
                        foreach ($cards as &$card) {
                            $card['icon'] = isset($card['icon']) && is_array($card['icon']) ? ($card['icon'][0] ?? null) : ($card['icon'] ?? null);
                        }
                        unset($card);
                    }
                }
                unset($cards);
            }

            // Process comparison_items images
            if (isset($data['comparison_items']) && is_array($data['comparison_items'])) {
                foreach ($data['comparison_items'] as $locale => &$items) {
                    if (is_array($items)) {
                        foreach ($items as &$item) {
                            $item['image'] = isset($item['image']) && is_array($item['image']) ? ($item['image'][0] ?? null) : ($item['image'] ?? null);
                        }
                        unset($item);
                    }
                }
                unset($items);
            }

            // Process tender_items icons
            if (isset($data['tender_items']) && is_array($data['tender_items'])) {
                foreach ($data['tender_items'] as $locale => &$items) {
                    if (is_array($items)) {
                        foreach ($items as &$item) {
                            $item['icon'] = isset($item['icon']) && is_array($item['icon']) ? ($item['icon'][0] ?? null) : ($item['icon'] ?? null);
                        }
                        unset($item);
                    }
                }
                unset($items);
            }

            // Process faq_items icons
            if (isset($data['faq_items']) && is_array($data['faq_items'])) {
                foreach ($data['faq_items'] as $locale => &$items) {
                    if (is_array($items)) {
                        foreach ($items as &$item) {
                            $item['icon'] = isset($item['icon']) && is_array($item['icon']) ? ($item['icon'][0] ?? null) : ($item['icon'] ?? null);
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
