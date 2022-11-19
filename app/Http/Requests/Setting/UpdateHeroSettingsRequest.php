<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroSettingsRequest extends FormRequest
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
            'hero_tagline' => ['required', 'string', 'max:100'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string', 'max:1000'],
            'hero_demo_button_text' => ['required', 'string', 'max:100'],
            'hero_demo_button_link' => ['required', 'string', 'max:1000'],
            'hero_get_started_button_text' => ['required', 'string', 'max:100'],
            'hero_get_started_button_link' => ['required', 'string', 'max:1000'],
        ];
    }
}