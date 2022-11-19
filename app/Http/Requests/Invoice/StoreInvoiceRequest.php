<?php

namespace App\Http\Requests\Invoice;

use App\Http\Requests\BaseRequest;

class StoreInvoiceRequest extends BaseRequest
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
            'client' => 'required',
            'reference' => 'nullable|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => $this->discountType == true ? 'nullable|numeric|min:1|max:100' : 'nullable|numeric|min:1|max:'.$this->subTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'orderTax' => 'required',
            'netTotal' => 'required|numeric|min:1',
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'deliveryPlace' => 'nullable|string|max:255',
            'account' => $this->addPayment == true ? 'required' : 'nullable',
            'paidAmount' => $this->addPayment == true ? 'required|min:1|max:'.$this->netTotal : 'nullable',
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
