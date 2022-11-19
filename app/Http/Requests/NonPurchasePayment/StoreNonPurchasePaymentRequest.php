<?php

namespace App\Http\Requests\NonPurchasePayment;

use App\Http\Requests\BaseRequest;

class StoreNonPurchasePaymentRequest extends BaseRequest
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
            'supplier' => 'required',
            'type' => 'required',
            'account' => $this->type == 1 ? 'required' : 'nullable',
            'availableBalance' => $this->type == 1 ? 'required|numeric|min:'.$this->amount : 'nullable',
            'amount' => 'required|numeric|min:1|max:'.$this->max,
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
