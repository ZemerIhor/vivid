<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;

class SubmitReview extends Component
{
    public $name; // Имя автора
    public $rating; // Рейтинг
    public $comment; // Текст отзыва

    // Правила валидации
    protected $rules = [
        'name' => 'required|string|max:255',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'required|string|max:1000',
    ];

    public function submit()
    {
        // Валидируем данные
        $this->validate();

        // Создаем новый отзыв
        $review = new Review();
        $review->name = $this->name;
        $review->rating = $this->rating;
        $review->setTranslation('comment', app()->getLocale(), $this->comment);
        $review->published = false; // По умолчанию не опубликован
        $review->save();

        // Показываем сообщение об успехе
        session()->flash('message', 'Ваш отзыв отправлен и ожидает модерации.');

        // Сбрасываем поля формы
        $this->reset(['name', 'rating', 'comment']);
    }

    public function render()
    {
        return view('livewire.submit-review');
    }
}
