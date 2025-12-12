<?php

namespace App\Filament\Pages;

use App\Settings\GlobalSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GlobalSet extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $settings = GlobalSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Global Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.global-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(GlobalSettings::class);

        $this->data = [
            'site_name' => $settings->site_name ?? 'My Website',
            'meta_description' => $settings->meta_description ?? 'Welcome to my website',
            'logo' => $settings->logo ?? '',
            'favicon' => $settings->favicon ?? '',
            'contact_email' => $settings->contact_email ?? 'contact@example.com',
            'feedback_form_title' => $settings->feedback_form_title ?? 'Feedback Form',
            'feedback_form_description' => $settings->feedback_form_description ?? 'Please leave your feedback',
            'feedback_form_image' => $settings->feedback_form_image ?? '',
            'home_title' => $settings->home_title ?? 'Home Page',
            'home_meta_description' => $settings->home_meta_description ?? 'Welcome to the home page of our website',
            'about_us_title' => $settings->about_us_title ?? 'About Us',
            'about_us_meta_description' => $settings->about_us_meta_description ?? 'Learn more about our company',
            'contacts_title' => $settings->contacts_title ?? 'Contacts',
            'contacts_meta_description' => $settings->contacts_meta_description ?? 'Get in touch with us',
            'faq_title' => $settings->faq_title ?? 'FAQ',
            'faq_meta_description' => $settings->faq_meta_description ?? 'Answers to frequently asked questions',
            'reviews_title' => $settings->reviews_title ?? 'Reviews',
            'reviews_meta_description' => $settings->reviews_meta_description ?? 'Read our customer reviews',
            'submit_review_title' => $settings->submit_review_title ?? 'Submit Review',
            'submit_review_meta_description' => $settings->submit_review_meta_description ?? 'Share your feedback about our products',
            'blog_title' => $settings->blog_title ?? 'Blog',
            'blog_meta_description' => $settings->blog_meta_description ?? 'Read our latest articles and news',
            'checkout_title' => $settings->checkout_title ?? 'Checkout',
            'checkout_meta_description' => $settings->checkout_meta_description ?? 'Complete your order quickly and easily',
            'checkout_success_title' => $settings->checkout_success_title ?? 'Order Successfully Placed',
            'checkout_success_meta_description' => $settings->checkout_success_meta_description ?? 'Thank you for your order!',
        ];

        Log::info('Global Settings form initialized', ['data' => $this->data]);

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Main Settings')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Site Name')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(4)
                                            ->required()
                                            ->maxLength(500),
                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->disk('public')
                                            ->directory('logos')
                                            ->nullable()
                                            ->rules(['nullable', 'mimes:jpeg,png,jpg,gif,webp,svg+xml'])
                                            ->image(),
                                        FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->disk('public')
                                            ->directory('favicons')
                                            ->nullable()
                                            ->rules(['nullable', 'mimes:jpeg,png,jpg,gif,webp,svg+xml'])
                                            ->image(),
                                        TextInput::make('contact_email')
                                            ->label('Contact Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                            ]),
                        Tabs\Tab::make('Feedback Form')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('feedback_form_title')
                                            ->label('Feedback Form Title')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('feedback_form_description')
                                            ->label('Feedback Form Description')
                                            ->rows(4)
                                            ->required()
                                            ->maxLength(500),
                                        FileUpload::make('feedback_form_image')
                                            ->label('Feedback Form Image')
                                            ->disk('public')
                                            ->directory('feedback-images')
                                            ->nullable()
                                            ->rules(['nullable', 'mimes:jpeg,png,jpg,gif,webp,svg+xml'])
                                            ->image(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tabs\Tab::make('Static Pages')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('home_title')->label('Home Title')->required()->maxLength(255),
                                        Textarea::make('home_meta_description')->label('Home Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('about_us_title')->label('About Us Title')->required()->maxLength(255),
                                        Textarea::make('about_us_meta_description')->label('About Us Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('contacts_title')->label('Contacts Title')->required()->maxLength(255),
                                        Textarea::make('contacts_meta_description')->label('Contacts Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('faq_title')->label('FAQ Title')->required()->maxLength(255),
                                        Textarea::make('faq_meta_description')->label('FAQ Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('reviews_title')->label('Reviews Title')->required()->maxLength(255),
                                        Textarea::make('reviews_meta_description')->label('Reviews Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('submit_review_title')->label('Submit Review Title')->required()->maxLength(255),
                                        Textarea::make('submit_review_meta_description')->label('Submit Review Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('blog_title')->label('Blog Title')->required()->maxLength(255),
                                        Textarea::make('blog_meta_description')->label('Blog Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('checkout_title')->label('Checkout Title')->required()->maxLength(255),
                                        Textarea::make('checkout_meta_description')->label('Checkout Meta Description')->rows(4)->required()->maxLength(500),
                                        TextInput::make('checkout_success_title')->label('Checkout Success Title')->required()->maxLength(255),
                                        Textarea::make('checkout_success_meta_description')->label('Checkout Success Meta Description')->rows(4)->required()->maxLength(500),
                                    ])
                                    ->columns(2),
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
                ->title('Settings saved')
                ->success()
                ->send();
        } catch (ValidationException $e) {
            Log::error('Validation errors in global settings', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Error')
                ->body(implode(', ', array_merge(...array_values($e->errors()))))
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Log::error('Error saving global settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function getNavigationLabel(): string
    {
        return 'Global Settings';
    }

    public static function getSlug(): string
    {
        return 'global-settings';
    }
}
