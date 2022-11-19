<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessStartSettingsRequest extends FormRequest
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
            'business_start_section_tagline' => ['required', 'string', 'max:100'],
            'business_start_section_title' => ['required', 'string', 'max:255'],
            'business_start_section_description' => ['required', 'string', 'max:1000'],
            'business_start_support_list' => ['required', 'array', 'min:1'],
        ];
    }
}
