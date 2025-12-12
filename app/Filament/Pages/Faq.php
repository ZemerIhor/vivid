<?php

namespace App\Filament\Pages;

use App\Settings\FaqSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Faq extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static string $view = 'filament.pages.faq';
    protected static string $settings = FaqSettings::class;
    protected static ?string $navigationLabel = 'FAQ Settings';

    public static function getSlug(): string
    {
        return 'faq';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(FaqSettings::class);

        $this->data = [
            'faq_blocks' => $settings->faq_blocks,
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Frequently Asked Questions')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('faq_blocks')
                                            ->label('FAQ Blocks')
                                            ->schema([
                                                FileUpload::make('main_image')
                                                    ->label('Main Image')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('faq-images/main'),
                                                TextInput::make('main_image_alt')
                                                    ->label('Alt text for main image')
                                                    ->maxLength(255),
                                                Repeater::make('items')
                                                    ->label('Questions and Answers')
                                                    ->schema([
                                                        FileUpload::make('thumbnail')
                                                            ->label('Thumbnail')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('faq-images/thumbnails'),
                                                        TextInput::make('thumbnail_alt')
                                                            ->label('Alt text for thumbnail')
                                                            ->maxLength(255),
                                                        TextInput::make('question')
                                                            ->label('Question')
                                                            ->maxLength(255),
                                                        Textarea::make('answer')
                                                            ->label('Answer')
                                                            ->maxLength(1000),
                                                    ])
                                                    ->columns(2)
                                                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                                                    ->collapsible()
                                                    ->cloneable(),
                                            ])
                                            ->columns(2)
                                            ->itemLabel(fn (array $state): ?string => $state['main_image_alt'] ?? null)
                                            ->collapsible()
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

            \Illuminate\Support\Facades\Log::info('FAQ Settings Form Data', ['data' => $data]);

            $settings = app(FaqSettings::class);
            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title('FAQ Settings Saved!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving FAQ settings', ['error' => $e->getMessage()]);
            Notification::make()
                ->title('Error saving FAQ settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
