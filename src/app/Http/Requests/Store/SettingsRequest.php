<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'area'    => ['required', 'string', 'max:50'], // 1つの文字列
            'mood'    => ['required', 'string', 'max:50'], // 1つの文字列
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => '店舗名は必須です。',
            'address.required' => '住所入力は必須です。',
            'area.required' => 'エリアを選んでください。',
            'mood.required' => 'カテゴリーを選んでください。',
        ];
    }
}
