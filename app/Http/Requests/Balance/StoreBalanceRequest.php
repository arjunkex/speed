<?php

namespace App\Http\Requests\Balance;

use App\Http\Requests\BaseRequest;

class StoreBalanceRequest extends BaseRequest
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
            'account' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
