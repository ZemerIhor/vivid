<?php

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
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
            'main_address' => $settings->main_address ?? '',
            'main_email' => $settings->main_email ?? '',
            'sales_phones' => $settings->sales_phones ?? [],
            'sales_email' => $settings->sales_email ?? '',
            'export_phone' => $settings->export_phone ?? '',
            'export_contact' => $settings->export_contact ?? '',
            'export_email' => $settings->export_email ?? '',
            'additional_emails' => collect($settings->additional_emails ?? [])->map(function ($value, $key) {
                return ['key' => $key, 'value' => $value];
            })->values()->toArray(),
            'map_image_alt' => $settings->map_image_alt ?? '',
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
                        Tabs\Tab::make('Main Info')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('main_address')
                                            ->label('Address')
                                            ->maxLength(255),
                                        TextInput::make('main_email')
                                            ->label('Main Email')
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make('Sales Department')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('sales_phones')
                                            ->label('Sales Phones')
                                            ->schema([
                                                TextInput::make('number')
                                                    ->label('Phone')
                                                    ->numeric()
                                                    ->maxLength(20),
                                            ])
                                            ->collapsible()
                                            ->cloneable(),
                                        TextInput::make('sales_email')
                                            ->label('Sales Email')
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make('Export Department')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('export_phone')
                                            ->label('Export Phone')
                                            ->numeric()
                                            ->maxLength(20),
                                        TextInput::make('export_contact')
                                            ->label('Export Contact')
                                            ->maxLength(255),
                                        TextInput::make('export_email')
                                            ->label('Export Email')
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make('Additional Emails')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('additional_emails')
                                            ->label('Additional Emails')
                                            ->schema([
                                                TextInput::make('key')
                                                    ->label('Email Key')
                                                    ->maxLength(50),
                                                TextInput::make('value')
                                                    ->label('Email Value')
                                                    ->email()
                                                    ->maxLength(255),
                                            ])
                                            ->collapsible()
                                            ->cloneable(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Map Settings')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('map_image_alt')
                                            ->label('Map Image Alt')
                                            ->maxLength(255),
                                        TextInput::make('map_latitude')
                                            ->label('Map Latitude')
                                            ->numeric(),
                                        TextInput::make('map_longitude')
                                            ->label('Map Longitude')
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

            // Преобразуем дополнительную почту в массив key => value
            $data['additional_emails'] = collect($data['additional_emails'])->pluck('value', 'key')->toArray();

            // Преобразуем телефоны в числа
            $data['sales_phones'] = collect($data['sales_phones'])
                ->map(fn($item) => (int) $item['number'])
                ->toArray();

            $data['export_phone'] = (int) $data['export_phone'];

            Log::info('Contact Settings Form Data', ['data' => $data]);

            $settings = app(ContactSettings::class);
            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title('Contacts saved successfully')
                ->success()
                ->send();
        } catch (ValidationException $e) {
            Log::error('Validation errors in contact settings', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Error saving contacts')
                ->body(implode(', ', array_merge(...array_values($e->errors()))))
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Log::error('Error saving contact settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title('Error saving contacts')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
