<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseRequest;

class StoreLoanPaymentRequest extends BaseRequest
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
            'referenceNo' => 'required|string|max:191',
            'loan' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|max:'.$this->availableBalance,
            'interest' => 'nullable|numeric|max:'.$this->availableBalance,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255'
        ];
    }
}
