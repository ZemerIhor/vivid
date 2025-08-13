<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|array',
            'comment.*' => 'required|string|max:1000|min:10',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения',
            'name.min' => 'Имя должно содержать минимум 2 символа',
            'name.max' => 'Имя не должно превышать 255 символов',
            'rating.required' => 'Рейтинг обязателен',
            'rating.between' => 'Рейтинг должен быть от 1 до 5',
            'comment.required' => 'Комментарий обязателен',
            'comment.*.required' => 'Комментарий обязателен для заполнения',
            'comment.*.min' => 'Комментарий должен содержать минимум 10 символов',
            'comment.*.max' => 'Комментарий не должен превышать 1000 символов',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'имя',
            'rating' => 'рейтинг',
            'comment' => 'комментарий',
        ];
    }
}
