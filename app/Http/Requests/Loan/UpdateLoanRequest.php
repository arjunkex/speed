<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseRequest;
use App\Models\Loan;

class UpdateLoanRequest extends BaseRequest
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

        $slug = $this->route('loan');

        $loan = Loan::where('slug', $slug)->first();
        $minAmount = $loan->totalPaid();

        return [
            'reason' => 'required|string|max:191',
            'referenceNo' => 'required|string|max:191|unique:loans,reference_no,'.$loan->id,
            'authority' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|min:'.$minAmount,
            'interest' => $this->loanType == 1 ? 'required|min:1|max:100' : 'nullable|numeric|min:0',
            'duration' => $this->loanType == 1 ? 'required|min:1' : 'nullable',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
