<?php

namespace App\Livewire;

use App\Http\Requests\StoreReviewRequest;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SubmitReview extends Component
{
    public $name; // Имя автора
    public $rating; // Рейтинг
    public $comment; // Текст отзыва
    public $isSubmitting = false; // Флаг отправки

    public function __construct(
        private ReviewService $reviewService
    ) {
        parent::__construct();
    }

    // Правила валидации
    protected $rules = [
        'name' => 'required|string|max:255|min:2',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'required|string|max:1000|min:10',
    ];

    protected $messages = [
        'name.required' => 'Имя обязательно для заполнения',
        'name.min' => 'Имя должно содержать минимум 2 символа',
        'name.max' => 'Имя не должно превышать 255 символов',
        'rating.required' => 'Пожалуйста, выберите рейтинг',
        'rating.between' => 'Рейтинг должен быть от 1 до 5 звезд',
        'comment.required' => 'Комментарий обязателен',
        'comment.min' => 'Комментарий должен содержать минимум 10 символов',
        'comment.max' => 'Комментарий не должен превышать 1000 символов',
    ];

    public function submit()
    {
        if ($this->isSubmitting) {
            return;
        }

        $this->isSubmitting = true;

        try {
            // Валидируем данные
            $this->validate();

            // Подготавливаем данные для создания отзыва
            $reviewData = [
                'name' => trim($this->name),
                'rating' => $this->rating,
                'comment' => [
                    app()->getLocale() => trim($this->comment)
                ]
            ];

            // Создаем отзыв через сервис
            $review = $this->reviewService->createReview($reviewData);

            // Показываем сообщение об успехе
            session()->flash('success', __('Ваш отзыв отправлен и ожидает модерации. Спасибо за обратную связь!'));

            // Сбрасываем поля формы
            $this->reset(['name', 'rating', 'comment']);

            // Логируем успешную отправку
            Log::info('Review submitted successfully', [
                'review_id' => $review->id,
                'rating' => $review->rating,
                'user_ip' => request()->ip(),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Ошибки валидации будут показаны автоматически
            throw $e;
        } catch (\Exception $e) {
            // Логируем ошибку
            Log::error('Error submitting review', [
                'error' => $e->getMessage(),
                'user_ip' => request()->ip(),
                'data' => [
                    'name' => $this->name,
                    'rating' => $this->rating,
                ]
            ]);

            // Показываем пользователю общую ошибку
            session()->flash('error', __('Произошла ошибка при отправке отзыва. Пожалуйста, попробуйте еще раз.'));
        } finally {
            $this->isSubmitting = false;
        }
    }

    /**
     * Обновление рейтинга в реальном времени
     */
    public function updatedRating($value)
    {
        if ($value < 1 || $value > 5) {
            $this->rating = null;
            $this->addError('rating', 'Рейтинг должен быть от 1 до 5 звезд');
        }
    }

    /**
     * Сброс формы
     */
    public function resetForm()
    {
        $this->reset(['name', 'rating', 'comment']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.submit-review');
    }
}
