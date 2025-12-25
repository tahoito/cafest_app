<?php

namespace App\Http\Requests\User;

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
            'name' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (!is_array($decoded) || count($decoded) === 0) {
                    $fail('エリアを1つ以上選んでください。');
                }
            }],
            'mood' => ['required', 'string', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (!is_array($decoded) || count($decoded) === 0) {
                    $fail('好みの雰囲気を1つ以上選んでください。');
                }
            }],
            'icon' => ['required', 'image', 'max:2048'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => '名前は必須です。',
            'area.required' => 'エリアを選んでください。',
            'mood.required' => '好きなカフェの雰囲気を選んでください。',
            'icon.required' => 'アイコン画像を選んでください。',
        ];
    }
}
