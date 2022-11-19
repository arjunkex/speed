<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeaturesSettingsRequest extends FormRequest
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
            'features_section_tagline' => ['required', 'string', 'max:100'],
            'features_section_title' => ['required', 'string', 'max:255'],
            'features_section_description' => ['required', 'string', 'max:1000'],
        ];
    }
}
