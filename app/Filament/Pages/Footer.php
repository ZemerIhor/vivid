<?php

namespace App\Filament\Pages;

use App\Settings\FooterSettings;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class Footer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.footer';

    protected static string $settings = FooterSettings::class;

    protected static ?string $navigationLabel = 'Налаштування футера';

    public static function getSlug(): string
    {
        return 'footer';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(FooterSettings::class);

        $this->data = [
            'phone' => $settings->phone ?? '',
            'email' => $settings->email ?? ['en' => '', 'pl' => ''],
            'address' => $settings->address ?? ['en' => '', 'pl' => ''],
            'copyright_text' => $settings->copyright_text ?? ['en' => '', 'pl' => ''],
            'social_links' => $settings->social_links ?? [],
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('phone')
                    ->label(__('Телефон'))
                    ->maxLength(255),
                Translate::make()
                    ->locales(['en', 'pl'])
                    ->schema([
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Textarea::make('address')
                            ->label(__('Адреса'))
                            ->required()
                            ->maxLength(1000),
                        TextInput::make('copyright_text')
                            ->label(__('Текст копірайту'))
                            ->required()
                            ->maxLength(255),
                    ]),
                Repeater::make('social_links')
                    ->label(__('Соцмережі'))
                    ->schema([
                        TextInput::make('url')
                            ->label(__('Посилання'))
                            ->url()
                            ->required()
                            ->maxLength(255),
                        Select::make('icon')
                            ->label(__('Іконка'))
                            ->options([
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'telegram' => 'Telegram',
                            ])
                            ->required()
                            ->selectablePlaceholder(false),
                    ])
                    ->default([])
                    ->collapsible()
                    ->reorderable()
                    ->cloneable(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Логирование сырых данных для отладки
            \Illuminate\Support\Facades\Log::info('Footer Settings Form Data (Raw)', ['data' => $data]);

            // Нормализация данных для переводимых и непереводимых полей
            $normalizedData = [
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
                'address' => [
                    'en' => $data['address']['en'] ?? '',
                    'pl' => $data['address']['pl'] ?? '',
                ],
                'copyright_text' => [
                    'en' => $data['copyright_text']['en'] ?? '',
                    'pl' => $data['copyright_text']['pl'] ?? '',
                ],
                'social_links' => $data['social_links'] ?? [],
                'sections' => [
                    'en' => $data['sections']['en'] ?? [],
                    'pl' => $data['sections']['pl'] ?? [],
                ],
            ];

            // Логирование нормализованных данных
            \Illuminate\Support\Facades\Log::info('Footer Settings Form Data (Normalized)', ['data' => $normalizedData]);

            $settings = app(FooterSettings::class);
            $settings->fill($normalizedData);
            $settings->save();

            Notification::make()
                ->title(__('Дані футера збережено!'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving Footer Settings', ['error' => $e->getMessage()]);
            Notification::make()
                ->title(__('Помилка збереження налаштувань'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
