<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseRequest;
use App\Models\LoanPayment;

class UpdateLoanPaymentRequest extends BaseRequest
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
        $slug = $this->route('loan_payment');
        $loanPayment = LoanPayment::where('slug', $slug)->first();

        $maxAmount = $this->loan['due'] + $loanPayment->amount;
        $availableBalance = $this->account['availableBalance'];
        if ($maxAmount >= $availableBalance) {
            $maxAmount = $availableBalance;
            if ($loanPayment->loanPaymentTransaction->cashbookAccount->slug == $this->account['slug']) {
                $maxAmount = $availableBalance + $loanPayment->loanPaymentTransaction->amount;
            }
        }

        return [
                'referenceNo' => 'required|string|max:191',
                'loan' => 'required',
                'account' => 'required',
                'amount' => 'required|numeric|max:'.$maxAmount,
                'date' => 'nullable|date_format:Y-m-d',
                'note' => 'nullable|string|max:255'
        ];
    }
}
