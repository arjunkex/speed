<?php

namespace App\Http\Requests\NonInvoicePayment;

use App\Http\Requests\BaseRequest;

class StoreNonInvoicePaymentRequest extends BaseRequest
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
            'type' => 'required',
            'account' => $this->type == 1 ? 'required' : 'nullable',
            'amount' => 'required|numeric|min:1|max:'.$this->max,
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255'
        ];
    }
}
