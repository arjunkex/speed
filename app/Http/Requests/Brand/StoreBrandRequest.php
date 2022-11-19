<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\BaseRequest;

class StoreBrandRequest extends BaseRequest
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
            'name' => 'required|string|max:255|unique:brands,name',
            'shortCode' => 'required|string|max:50|unique:brands,code',
            'note' => 'nullable|string|max:255',
        ];
    }
}
