<?php

namespace App\Http\Requests\Balance;

use App\Http\Requests\BaseRequest;
use App\Models\AccountTransaction;

class UpdateBalanceRequest extends BaseRequest
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
        $slug = $this->route('balance');

        $transaction = AccountTransaction::where('slug', $slug)->first();

        return [
            'account' => 'required',
            'amount' => 'required|numeric|min:'.$transaction->amount,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
            'status' => 'required',
        ];
    }
}
