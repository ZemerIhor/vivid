<?php

namespace App\Livewire;

use App\Services\ReviewService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SubmitReview extends Component
{
    public $name;
    public $rating;
    public $comment;
    public $isSubmitting = false;
    private ReviewService $reviewService;

    public function boot(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    // Правила валидации
    protected $rules = [
        'name' => 'required|string|max:255|min:2',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'required|string|max:1000|min:10',
    ];

    protected function messages()
    {
        return [
            'name.required' => __('messages.submit_review.validation.name_required'),
            'name.min' => __('messages.submit_review.validation.name_min', ['min' => 2]),
            'name.max' => __('messages.submit_review.validation.name_max', ['max' => 255]),
            'rating.required' => __('messages.submit_review.validation.rating_required'),
            'rating.between' => __('messages.submit_review.validation.rating_between', ['min' => 1, 'max' => 5]),
            'comment.required' => __('messages.submit_review.validation.comment_required'),
            'comment.min' => __('messages.submit_review.validation.comment_min', ['min' => 10]),
            'comment.max' => __('messages.submit_review.validation.comment_max', ['max' => 1000]),
        ];
    }

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
            session()->flash('success', __('messages.submit_review.success_message'));

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
            session()->flash('error', __('messages.submit_review.error_message'));
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
            $this->addError('rating', __('messages.submit_review.validation.rating_between', ['min' => 1, 'max' => 5]));
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
