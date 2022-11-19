<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutUsSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'about_us_tagline' => ['required', 'string', 'max:100'],
            'about_us_title' => ['required', 'string', 'max:255'],
            'about_us_description' => ['required', 'string', 'max:1000'],
        ];
    }
}
