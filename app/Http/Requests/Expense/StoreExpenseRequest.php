<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\BaseRequest;

class StoreExpenseRequest extends BaseRequest
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

            'reason' => 'required|string|max:255',
            'subCategory' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|max:'.$this->availableBalance,
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
