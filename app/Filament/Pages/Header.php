<?php

namespace App\Filament\Pages;

use App\Settings\HeaderSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

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
                Section::make(__('Социальные сети'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'pl'])
                            ->schema([
                                TextInput::make('instagram_url')
                                    ->label(__('Instagram URL'))
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('facebook_url')
                                    ->label(__('Facebook URL'))
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('telegram_url')
                                    ->label(__('Telegram URL'))
                                    ->url()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(),
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
                ->title(__('Дані шапки збережено!'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving Header Settings', ['error' => $e->getMessage()]);
            Notification::make()
                ->title(__('Помилка збереження налаштувань'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
