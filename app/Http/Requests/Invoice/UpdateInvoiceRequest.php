<?php

namespace App\Http\Requests\Invoice;

use App\Http\Requests\BaseRequest;
use App\Models\Invoice;
use App\Rules\MinTotal;

class UpdateInvoiceRequest extends BaseRequest
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
        $slug = $this->route('invoice');

        $invoice = Invoice::where('slug', $slug)->with('invoiceProducts.product', 'invoiceReturn')->first();
        $totalPaid = $invoice->invoiceTotalPaid();
        $minAmount = ! isset($invoice->invoiceReturn) ? $totalPaid : $totalPaid - $invoice->invoiceReturn->returnTransaction->amount;

        return [

            'client' => 'required',
            'reference' => 'required|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => $this->discountType == true ? 'nullable|numeric|min:1|max:100' : 'nullable|numeric|min:1|max:'.$this->subTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'netTotal' => ['required', 'numeric', new MinTotal($minAmount, $this->netTotal)],
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'deliveryPlace' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',

        ];
    }
}
