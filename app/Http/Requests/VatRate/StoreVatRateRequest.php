<?php

namespace App\Http\Requests\VatRate;

use App\Http\Requests\BaseRequest;

class StoreVatRateRequest extends BaseRequest
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
            'name' => 'required|string|max:50|unique:vat_rates',
            'code' => 'required|string|max:50|unique:vat_rates,code',
            'rate' => 'required|numeric|max:90',
            'note' => 'nullable|string|max:255'
        ];
    }
}
