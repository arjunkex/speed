<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseRequest;
use App\Rules\MinTotal;
use App\Models\Purchase;

class UpdatePurchaseRequest extends BaseRequest
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
        $slug  = $this->route('purchase');

        $purchase = Purchase::where('slug', $slug)->with('purchaseProducts.product')->first();
        $totalPaid = $purchase->purchaseTotalPaid();
        $minAmount = ! isset($purchase->purchaseReturn) ? $totalPaid : $totalPaid - $purchase->purchaseReturn->returnTransaction->amount;

        return [
            'supplier' => 'required',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => 'nullable|numeric|min:1|max:'.$this->rowSubTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'orderTax' => 'required',
            'netTotal' => ['required', 'numeric', new MinTotal($minAmount, $this->netTotal)],
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'purchaseDate' => 'nullable|date_format:Y-m-d',
            'poDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
