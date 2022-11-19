<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseRequest;
use App\Rules\PurchaseTotalPaid;

class StorePurchaseRequest extends BaseRequest
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
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => 'nullable|numeric|min:1|max:'.$this->subTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'orderTax' => 'required',
            'netTotal' => 'required|numeric|min:1',
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'account' => $this->addPayment == true ? 'required' : 'nullable',
            'availableBalance' => $this->addPayment == true ? 'required|numeric' : 'nullable',
            'totalPaid' => [$this->addPayment != true ? 'nullable' : 'required', new PurchaseTotalPaid($this->availableBalance)],
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'purchaseDate' => 'nullable|date_format:Y-m-d',
            'poDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
