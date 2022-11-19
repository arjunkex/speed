<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGetStartedSettingsRequest extends FormRequest
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
            'get_started_box_title' => ['required', 'string', 'max:255'],
            'get_started_box_description' => ['required', 'string', 'max:1000'],
            'get_started_box_button_text' => ['required', 'string', 'max:100'],
            'get_started_box_button_link' => ['required', 'string', 'max:1000'],
        ];
    }
}