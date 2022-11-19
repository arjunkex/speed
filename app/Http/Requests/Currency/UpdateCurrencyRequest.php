<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\BaseRequest;
use App\Models\Currency;

class UpdateCurrencyRequest extends BaseRequest
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
        $slug = $this->route('currency');

        $currency = Currency::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:currencies,name,'.$currency->id,
            'code' => 'required|string|max:50|unique:currencies,code,'.$currency->id,
            'symbol' => 'required|string|max:50|unique:currencies,symbol,'.$currency->id,
            'note' => 'nullable|string|max:255',
        ];
    }
}
