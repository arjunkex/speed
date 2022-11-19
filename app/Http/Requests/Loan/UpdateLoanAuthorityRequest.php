<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseRequest;
use App\Models\LoanAuthority;

class UpdateLoanAuthorityRequest extends BaseRequest
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

        $slug = $this->route('loan_authority');
        $loanAuthority = LoanAuthority::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:150|unique:loan_authorities,name,'.$loanAuthority->id,
            'contactNumber' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'ccLoanLimit' => 'required|numeric|min:100000|max:999999999999',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ];
    }
}
