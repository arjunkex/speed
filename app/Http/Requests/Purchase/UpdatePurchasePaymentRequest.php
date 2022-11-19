<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseRequest;

class UpdatePurchasePaymentRequest extends BaseRequest
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
            'purchaseNo' => 'required',
            'purchase' => 'required',
            'account' => 'required',
            'availableBalance' => 'required|numeric|min:'.$this->paidAmount,
            'paidAmount' => 'required|numeric|min:'.$this->minAmount.'|max:'.$this->maxAmount,
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
