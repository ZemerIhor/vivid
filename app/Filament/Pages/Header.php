<?php

namespace App\Filament\Pages;

use App\Settings\HeaderSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Header extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static string $view = 'filament.pages.header';
    protected static string $settings = HeaderSettings::class;
    protected static ?string $navigationLabel = 'Header Settings';

    public static function getSlug(): string
    {
        return 'header';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(HeaderSettings::class);

        $this->data = [
            'instagram_url' => $settings->instagram_url,
            'facebook_url' => $settings->facebook_url,
            'telegram_url' => $settings->telegram_url,
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Social Media')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('instagram_url')
                                            ->label('Instagram URL')
                                            ->url()
                                            ->maxLength(255),
                                        TextInput::make('facebook_url')
                                            ->label('Facebook URL')
                                            ->url()
                                            ->maxLength(255),
                                        TextInput::make('telegram_url')
                                            ->label('Telegram URL')
                                            ->url()
                                            ->maxLength(255),
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

            \Illuminate\Support\Facades\Log::info('Header Settings Form Data', ['data' => $data]);

            $settings = app(HeaderSettings::class);
            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title('Header Settings Saved!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving Header Settings', ['error' => $e->getMessage()]);
            Notification::make()
                ->title('Error saving Header Settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
