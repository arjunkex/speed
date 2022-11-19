<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\BaseRequest;
use App\Models\Expense;


class UpdateExpenseRequest extends BaseRequest
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

        $slug = $this->route('expense');

        $expense = Expense::with('expSubCategory', 'expTransaction.cashbookAccount')->where('slug', $slug)->first();
        $availableBalance = 99999999;

        if (isset($this->account['availableBalance'])) {
            $availableBalance = $expense->expTransaction->amount + $this->account['availableBalance'];
        }

        return [

            'reason' => 'required|string|max:255',
            'subCategory' => 'required',
            'account' => 'required',
            'amount' => isset($this->account) ? 'required|numeric|max:'.$availableBalance : 'nullable',
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
