<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\BaseRequest;

class StoreCurrencyRequest extends BaseRequest
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
            'name' => 'required|string|max:50|unique:currencies',
            'code' => 'required|string|max:50|unique:currencies,code',
            'symbol' => 'required|string|max:50|unique:currencies,symbol',
            'note' => 'nullable|string|max:255',
        ];
    }
}
