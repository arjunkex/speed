<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseRequest;

class StoreLoanAuthorityRequest extends BaseRequest
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
            'name' => 'required|string|max:150|unique:loan_authorities',
            'contactNumber' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'ccLoanLimit' => 'required|numeric|min:100000|max:999999999999',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255'
        ];
    }
}
