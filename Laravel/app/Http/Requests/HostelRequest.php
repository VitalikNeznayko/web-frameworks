<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class HostelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле «:attribute» є обовʼязковим.',
            'string' => 'Поле «:attribute» має бути рядком.',
            'integer' => 'Поле «:attribute» має бути цілим числом.',
            'numeric' => 'Поле «:attribute» має бути числом.',
            'boolean' => 'Поле «:attribute» має бути так/ні.',
            'array' => 'Поле «:attribute» має бути списком.',
            'email' => 'Поле «:attribute» має містити коректну email-адресу.',
            'date' => 'Поле «:attribute» має бути коректною датою.',
            'max.string' => 'Поле «:attribute» не може перевищувати :max символів.',
            'max.numeric' => 'Поле «:attribute» не може бути більшим за :max.',
            'min.numeric' => 'Поле «:attribute» не може бути меншим за :min.',
            'unique' => 'Таке значення поля «:attribute» вже існує.',
            'exists' => 'Обране значення поля «:attribute» не існує.',
            'in' => 'Обране значення поля «:attribute» некоректне.',
            'after' => 'Поле «:attribute» має бути датою після «:date».',
            'before' => 'Поле «:attribute» має бути датою до «:date».',
        ];
    }
}
