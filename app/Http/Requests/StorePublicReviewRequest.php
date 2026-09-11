<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StorePublicReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'doctor_id' => [
                'required',
                'integer',
                Rule::exists('doctors', 'id')->where(static fn ($q) => $q->where('is_published', true)),
            ],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'agree' => ['accepted'],
            'website' => ['prohibited'],
            'g-recaptcha-response' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'agree.accepted' => 'Необходимо согласие на обработку персональных данных.',
            'doctor_id.required' => 'Выберите врача, о котором ваш отзыв.',
            'doctor_id.exists' => 'Выбранный врач недоступен для отзыва.',
            'website.prohibited' => 'Не удалось отправить форму. Обновите страницу и попробуйте снова.',
        ];
    }
}
