<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Или проверка прав админа: auth()->user()?->can('create', BlogPost::class)
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|max:255|min:3',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|array',
            'excerpt.*' => 'nullable|string|max:500',
            'content' => 'required|array',
            'content.*' => 'required|string|min:50',
            'banner' => 'nullable|string|max:255',
            'seo_title' => 'nullable|array',
            'seo_title.*' => 'nullable|string|max:60',
            'seo_description' => 'nullable|array',
            'seo_description.*' => 'nullable|string|max:160',
            'published' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обязателен',
            'title.*.required' => 'Заголовок обязателен для всех языков',
            'title.*.min' => 'Заголовок должен содержать минимум 3 символа',
            'title.*.max' => 'Заголовок не должен превышать 255 символов',
            'slug.required' => 'Slug обязателен',
            'slug.unique' => 'Такой slug уже существует',
            'content.required' => 'Содержимое обязательно',
            'content.*.required' => 'Содержимое обязательно для всех языков',
            'content.*.min' => 'Содержимое должно содержать минимум 50 символов',
            'seo_title.*.max' => 'SEO заголовок не должен превышать 60 символов',
            'seo_description.*.max' => 'SEO описание не должно превышать 160 символов',
        ];
    }
}
