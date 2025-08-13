<?php

namespace App\Livewire\Elements;

use Livewire\Component;

class PromoBoxElement extends Component
{
    public $content;

    public function mount($content)
    {
        $this->content = $content;
    }

    public function render()
    {
        return view('livewire.elements.promo-box-element');
    }

    // Название блока в интерфейсе
    public static function label(): string
    {
        return 'Промо-блок';
    }

    // Иконка блока
    public static function icon(): string
    {
        return 'heroicon-o-megaphone';
    }

    // Поля формы для редактирования в админке
    public static function formSchema(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('title')
                ->label('Заголовок')
                ->required(),

            \Filament\Forms\Components\Textarea::make('subtitle')
                ->label('Описание'),

            \Filament\Forms\Components\TextInput::make('button_text')
                ->label('Текст кнопки'),

            \Filament\Forms\Components\TextInput::make('button_url')
                ->label('Ссылка кнопки'),
        ];
    }
}
