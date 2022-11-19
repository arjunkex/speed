<?php

namespace App\Http\Requests\Page;

use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'type' => [
                'required', 'integer', 'in:'.implode(',', [
                    Page::TYPE_INFORMATION,
                    Page::TYPE_NEED_HELP,
                ]),
            ],
            'content' => ['required', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }
}
