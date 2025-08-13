<?php

namespace App\Filament\Pages;

use App\Settings\FaqSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

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
                Section::make(__('Часто задаваемые вопросы'))
                    ->schema([
                        Translate::make()
                            ->locales(['en', 'pl'])
                            ->schema([
                                Repeater::make('faq_blocks')
                                    ->label(__('Блоки FAQ'))
                                    ->schema([
                                        FileUpload::make('main_image')
                                            ->label(__('Основное изображение'))
                                            ->image()
                                            ->disk('public')
                                            ->directory('faq-images/main')
                                            ->required(),
                                        TextInput::make('main_image_alt')
                                            ->label(__('Alt-текст основного изображения'))
                                            ->required()
                                            ->maxLength(255),
                                        Repeater::make('items')
                                            ->label(__('Вопросы и ответы'))
                                            ->schema([
                                                FileUpload::make('thumbnail')
                                                    ->label(__('Миниатюра'))
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('faq-images/thumbnails')
                                                    ->required(),
                                                TextInput::make('thumbnail_alt')
                                                    ->label(__('Alt-текст миниатюры'))
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('question')
                                                    ->label(__('Вопрос'))
                                                    ->required()
                                                    ->maxLength(255),
                                                Textarea::make('answer')
                                                    ->label(__('Ответ'))
                                                    ->required()
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
                    ])
                    ->collapsible(),
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
                ->title(__('Настройки FAQ сохранены!'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка сохранения настроек FAQ', ['error' => $e->getMessage()]);
            Notification::make()
                ->title(__('Ошибка сохранения настроек'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
