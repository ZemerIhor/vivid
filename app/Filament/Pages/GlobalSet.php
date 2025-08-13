<?php

namespace App\Filament\Pages;

use App\Settings\GlobalSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GlobalSet extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $settings = GlobalSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Глобальные настройки';
    protected static ?string $navigationGroup = 'Настройки';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.global-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(GlobalSettings::class);

        // Инициализация данных формы из настроек
        $this->data = [
            'site_name' => $settings->site_name ?? ['en' => '', 'pl' => ''],
            'meta_description' => $settings->meta_description ?? ['en' => '', 'pl' => ''],
            'logo' => $settings->logo ?? '',
            'favicon' => $settings->favicon ?? '',
            'contact_email' => $settings->contact_email ?? 'contact@example.com',
            'feedback_form_title' => $settings->feedback_form_title ?? ['en' => '', 'pl' => ''],
            'feedback_form_description' => $settings->feedback_form_description ?? ['en' => '', 'pl' => ''],
            'feedback_form_image' => $settings->feedback_form_image ?? '',
            'home_title' => $settings->home_title ?? ['en' => 'Home Page', 'pl' => 'Головна сторінка'],
            'home_meta_description' => $settings->home_meta_description ?? ['en' => 'Welcome to our site’s home page', 'pl' => 'Ласкаво просимо на головну сторінку нашого сайту'],
            'about_us_title' => $settings->about_us_title ?? ['en' => 'About Us', 'pl' => 'Про нас'],
            'about_us_meta_description' => $settings->about_us_meta_description ?? ['en' => 'Learn more about our company', 'pl' => 'Дізнайтесь більше про нашу компанію'],
            'contacts_title' => $settings->contacts_title ?? ['en' => 'Contacts', 'pl' => 'Контакти'],
            'contacts_meta_description' => $settings->contacts_meta_description ?? ['en' => 'Get in touch with us', 'pl' => 'Зв’яжіться з нами'],
            'faq_title' => $settings->faq_title ?? ['en' => 'FAQ', 'pl' => 'Поширені запитання'],
            'faq_meta_description' => $settings->faq_meta_description ?? ['en' => 'Answers to frequently asked questions', 'pl' => 'Відповіді на поширені запитання'],
            'reviews_title' => $settings->reviews_title ?? ['en' => 'Reviews', 'pl' => 'Відгуки'],
            'reviews_meta_description' => $settings->reviews_meta_description ?? ['en' => 'Read our customer reviews', 'pl' => 'Читайте відгуки наших клієнтів'],
            'submit_review_title' => $settings->submit_review_title ?? ['en' => 'Submit Review', 'pl' => 'Залишити відгук'],
            'submit_review_meta_description' => $settings->submit_review_meta_description ?? ['en' => 'Share your feedback about our products', 'pl' => 'Поділіться своїм відгуком про наші продукти'],
            'blog_title' => $settings->blog_title ?? ['en' => 'Blog', 'pl' => 'Блог'],
            'blog_meta_description' => $settings->blog_meta_description ?? ['en' => 'Read our latest articles and news', 'pl' => 'Читайте наші останні статті та новини'],
            'checkout_title' => $settings->checkout_title ?? ['en' => 'Checkout', 'pl' => 'Оформлення замовлення'],
            'checkout_meta_description' => $settings->checkout_meta_description ?? ['en' => 'Complete your order quickly and easily', 'pl' => 'Оформіть ваше замовлення швидко та зручно'],
            'checkout_success_title' => $settings->checkout_success_title ?? ['en' => 'Order Successfully Placed', 'pl' => 'Замовлення успішно оформлено'],
            'checkout_success_meta_description' => $settings->checkout_success_meta_description ?? ['en' => 'Thank you for your order!', 'pl' => 'Дякуємо за ваше замовлення!'],
        ];

        Log::info('Global Settings form initialized', ['data' => $this->data]);

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('messages.settings.main_section'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'pl'])
                            ->schema([
                                TextInput::make('site_name')
                                    ->label(__('messages.settings.site_name'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('meta_description')
                                    ->label(__('messages.settings.meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                            ]),
                        FileUpload::make('logo')
                            ->label(__('messages.settings.logo'))
                            ->image()
                            ->disk('public')
                            ->directory('logos')
                            ->nullable()
                            ->rules(['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp']),
                        FileUpload::make('favicon')
                            ->label(__('messages.settings.favicon'))
                            ->image()
                            ->disk('public')
                            ->directory('favicons')
                            ->nullable()
                            ->rules(['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp']),
                        TextInput::make('contact_email')
                            ->label(__('messages.settings.contact_email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])
                    ->collapsible()
                    ->columns(2),

                Section::make(__('messages.settings.feedback_form_section'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'pl'])
                            ->schema([
                                TextInput::make('feedback_form_title')
                                    ->label(__('messages.settings.feedback_form_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('feedback_form_description')
                                    ->label(__('messages.settings.feedback_form_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                            ]),
                        FileUpload::make('feedback_form_image')
                            ->label(__('messages.settings.feedback_form_image'))
                            ->image()
                            ->disk('public')
                            ->directory('feedback-images')
                            ->nullable()
                            ->rules(['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp']),
                    ])
                    ->collapsible()
                    ->columns(2),

                Section::make(__('messages.settings.static_pages_section'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'pl'])
                            ->schema([
                                TextInput::make('home_title')
                                    ->label(__('messages.settings.home_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('home_meta_description')
                                    ->label(__('messages.settings.home_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('about_us_title')
                                    ->label(__('messages.settings.about_us_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('about_us_meta_description')
                                    ->label(__('messages.settings.about_us_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('contacts_title')
                                    ->label(__('messages.settings.contacts_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('contacts_meta_description')
                                    ->label(__('messages.settings.contacts_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('faq_title')
                                    ->label(__('messages.settings.faq_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('faq_meta_description')
                                    ->label(__('messages.settings.faq_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('reviews_title')
                                    ->label(__('messages.settings.reviews_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('reviews_meta_description')
                                    ->label(__('messages.settings.reviews_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('submit_review_title')
                                    ->label(__('messages.settings.submit_review_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('submit_review_meta_description')
                                    ->label(__('messages.settings.submit_review_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('blog_title')
                                    ->label(__('messages.settings.blog_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('blog_meta_description')
                                    ->label(__('messages.settings.blog_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('checkout_title')
                                    ->label(__('messages.settings.checkout_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('checkout_meta_description')
                                    ->label(__('messages.settings.checkout_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('checkout_success_title')
                                    ->label(__('messages.settings.checkout_success_title'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('checkout_success_meta_description')
                                    ->label(__('messages.settings.checkout_success_meta_description'))
                                    ->rows(4)
                                    ->required()
                                    ->maxLength(500),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Логирование MIME-типа для изображений
            foreach (['logo', 'favicon', 'feedback_form_image'] as $field) {
                if (isset($data[$field]) && is_object($data[$field])) {
                    Log::info("MIME type for {$field}", ['mime' => $data[$field]->getMimeType()]);
                }
            }

            Log::info('Global Settings Form Data', ['data' => $data]);

            $settings = app(GlobalSettings::class);
            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title(__('messages.settings.saved'))
                ->success()
                ->send();
        } catch (ValidationException $e) {
            Log::error('Ошибки валидации в глобальных настройках', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            Notification::make()
                ->title(__('messages.settings.error'))
                ->body(implode(', ', array_merge(...array_values($e->errors()))))
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Log::error('Ошибка сохранения глобальных настроек', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title(__('messages.settings.error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.settings.navigation_label');
    }

    public static function getSlug(): string
    {
        return 'global-settings';
    }
}
