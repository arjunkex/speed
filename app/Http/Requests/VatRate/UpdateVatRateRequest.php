<?php

namespace App\Http\Requests\VatRate;

use App\Http\Requests\BaseRequest;
use App\Models\VatRate;

class UpdateVatRateRequest extends BaseRequest
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
        $slug  = $this->route('vat_rate');

        $vatRate = VatRate::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:vat_rates,name,'.$vatRate->id,
            'code' => 'required|string|max:50|unique:currencies,code,'.$vatRate->id,
            'rate' => 'required|numeric|max:90',
            'note' => 'nullable|string|max:255'
        ];
    }
}
