<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseListReource extends JsonResource
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
            'code' => $this->purchase_no,
            'purchaseNo' => config('config.purchasePrefix').'-'.$this->purchase_no,
            'slug' => $this->slug,
            'supplierName' => $this->supplier->name,
            'supplierPhone' => $this->supplier->phone,
            'transport' => $this->transport > 0 ? $this->transport : 0,
            'tax' => $this->taxAmount(),
            'taxRate' => $this->purchaseTax->rate,
            'subTotal' => $this->sub_total,
            'purchaseTotal' => $this->purchaseTotal(),
            'totalDiscount' => $this->discount > 0 ? $this->discount : 0,
            'totalPaid' => $this->purchaseTotalPaid(),
            'due' => round($this->totalDue() > 0 ? $this->totalDue() : 0, 2),
            'purchaseDate' => $this->purchase_date,
            'accountReceivable' => isset($this->purchaseReturn->returnTransaction) ? $this->purchaseReturn->returnTransaction->amount : null,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}
