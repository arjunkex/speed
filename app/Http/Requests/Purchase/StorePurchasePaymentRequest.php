<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseRequest;

class StorePurchasePaymentRequest extends BaseRequest
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
            'selectedPurchases' => 'required|array|min:1',
            'selectedPurchases.*' => 'required|distinct',
            'account' => 'required',
            'availableBalance' => 'required|numeric|min:'.$this->finalTotal,
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
