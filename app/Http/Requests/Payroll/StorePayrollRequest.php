<?php

namespace App\Http\Requests\Payroll;

use App\Http\Requests\BaseRequest;

class StorePayrollRequest extends BaseRequest
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
            'employee' => 'required',
            'account' => 'required',
            'salaryMonth' => 'nullable|string|max:255',
            'chequeNo' => 'nullable|string|max:255',
            'deductionAmount' => 'nullable|numeric|min:0',
            'deductionReason' => 'nullable|string|max:255',
            'mobileBill' => 'nullable|numeric|min:0',
            'foodBill' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'festivalBonus' => 'nullable|numeric|min:0',
            'travelAllowance' => 'nullable|numeric|min:0',
            'others' => 'nullable|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
            'totalSalary' => 'required|numeric|min:0|max:'.$this->availableBalance,
            'salaryDate' => 'nullable|date|date_format:Y-m-d',
            'note' => 'nullable|string|max:255'
        ];
    }
}
