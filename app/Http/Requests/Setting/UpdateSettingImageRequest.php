<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingImageRequest extends FormRequest
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
            'image' => ['nullable'],
            'title' => ['nullable', 'required_if:type,why_us_cards,features,explorers,all_features,testimonials', 'string', 'max:255'],
            'description' => ['nullable', 'required_if:type,why_us_cards,all_features,testimonials', 'string', 'max:1000'],
            'name' => ['nullable', 'required_if:type,testimonials,brands', 'string', 'max:255'],
            'image_align_left' => ['nullable', 'required_if:type,explorers', 'boolean'],
            'points' => ['nullable', 'required_if:type,explorers'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'status' => ['required'],
        ];
    }
}