<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'purchaseNo' => $this->purchase_no,
            'slug' => $this->slug,
            'supplier' => new SupplierListReource($this->supplier),
            'transport' => $this->transport,
            'tax' => $this->taxAmount(),
            'taxType' => $this->purchaseTax,
            'subTotal' => $this->sub_total,
            'purchaseTotal' => $this->purchaseTotal(),
            'totalDiscount' => $this->discount,
            'totalPaid' => $this->purchaseTotalPaid(),
            'due' => $this->totalDue(),
            'poReference' => $this->po_reference,
            'paymentTerms' => $this->payment_terms,
            'poDate' => $this->po_date,
            'purchaseDate' => $this->purchase_date,
            'payments' => $this->purchasePayments,
            'products' => PurchaseProductResource::collection($this->purchaseProducts),
            'purchaseReturn' => new PurchaseReturnListReource($this->purchaseReturn),
            'accountReceivable' => isset($this->purchaseReturn->returnTransaction) ? $this->purchaseReturn->returnTransaction->amount : null,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}
