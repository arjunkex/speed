<?php

namespace App\Http\Requests\Quotation;

use App\Http\Requests\BaseRequest;

class StoreQuotationRequest extends BaseRequest
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
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => $this->discountType == true ? 'nullable|numeric|min:1|max:100' : 'nullable|numeric|min:1|max:'.$this->netTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'netTotal' => 'required|numeric|min:1',
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'orderTax' => 'required',
            'deliveryPlace' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
