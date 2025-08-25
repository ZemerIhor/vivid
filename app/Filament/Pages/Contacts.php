<?php

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Contacts extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static string $view = 'filament.pages.contacts';
    protected static ?string $navigationLabel = 'Contact Settings';
    public static function getSlug(): string
    {
        return 'contacts';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(ContactSettings::class);

        $this->data = [
            'main_address' => $settings->main_address ?? ['en' => '', 'pl' => ''],
            'main_email' => $settings->main_email ?? '',
            'sales_phones' => $settings->sales_phones ?? [],
            'sales_email' => $settings->sales_email ?? '',
            'export_phone' => $settings->export_phone ?? '',
            'export_contact' => $settings->export_contact ?? ['en' => '', 'pl' => ''],
            'export_email' => $settings->export_email ?? '',
            'additional_emails' => collect($settings->additional_emails ?? [])->map(function ($value, $key) {
                return ['key' => $key, 'value' => $value];
            })->values()->toArray(),
            'map_image_alt' => $settings->map_image_alt ?? ['en' => '', 'pl' => ''],
            'map_latitude' => $settings->map_latitude ?? '',
            'map_longitude' => $settings->map_longitude ?? '',
        ];

        Log::info('Contacts form initialized', ['data' => $this->data]);
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('messages.contacts.main_info'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('main_address')
                                                    ->label(__('messages.contacts.address'))
                                                    ->maxLength(255),
                                            ]),
                                        TextInput::make('main_email')
                                            ->label(__('messages.contacts.main_email'))
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('messages.contacts.sales_department'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('sales_phones')
                                            ->label(__('messages.contacts.sales_phones'))
                                            ->schema([
                                                TextInput::make('number')
                                                    ->label(__('messages.contacts.phone'))
                                                    ->tel()
                                                    ->maxLength(20),
                                            ])
                                            ->collapsible()
                                            ->cloneable(),
                                        TextInput::make('sales_email')
                                            ->label(__('messages.contacts.sales_email'))
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('messages.contacts.export_department'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('export_phone')
                                            ->label(__('messages.contacts.export_phone'))
                                            ->tel()
                                            ->maxLength(20),
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('export_contact')
                                                    ->label(__('messages.contacts.export_contact'))
                                                    ->maxLength(255),
                                            ]),
                                        TextInput::make('export_email')
                                            ->label(__('messages.contacts.export_email'))
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('messages.contacts.additional_emails'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('additional_emails')
                                            ->label(__('messages.contacts.additional_emails'))
                                            ->schema([
                                                TextInput::make('key')
                                                    ->label(__('messages.contacts.email_key'))
                                                    ->maxLength(50),
                                                TextInput::make('value')
                                                    ->label(__('messages.contacts.email_value'))
                                                    ->email()
                                                    ->maxLength(255),
                                            ])
                                            ->collapsible()
                                            ->cloneable(),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('messages.contacts.map_settings'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Translate::make()
                                            ->locales(['en', 'pl'])
                                            ->schema([
                                                TextInput::make('map_image_alt')
                                                    ->label(__('messages.contacts.map_alt'))
                                                    ->maxLength(255),
                                            ]),
                                        TextInput::make('map_latitude')
                                            ->label(__('messages.contacts.map_latitude'))
                                            ->numeric(),
                                        TextInput::make('map_longitude')
                                            ->label(__('messages.contacts.map_longitude'))
                                            ->numeric(),
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
            $data['additional_emails'] = collect($data['additional_emails'])->pluck('value', 'key')->toArray();

            Log::info('Contact Settings Form Data', ['data' => $data]);

            $settings = app(ContactSettings::class);
            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title(__('messages.contacts.saved'))
                ->success()
                ->send();
        } catch (ValidationException $e) {
            Log::error('Ошибки валидации в настройках контактов', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            Notification::make()
                ->title(__('messages.contacts.error'))
                ->body(implode(', ', array_merge(...array_values($e->errors()))))
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Log::error('Ошибка сохранения настроек контактов', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title(__('messages.contacts.error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
