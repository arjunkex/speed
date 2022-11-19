<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnResource extends JsonResource
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
            'reason' => $this->reason,
            'slug' => $this->slug,
            'returnNo' => $this->code,
            'totalReturn' => $this->total_return,
            'purchase' => new PurchaseListReource($this->purchase),
            'supplier' => new SupplierListReource($this->purchase->supplier),
            'returnProducts' => PurchaseReturnProductReource::collection($this->purchaseReturnProducts),
            'accountReceivable' => isset($this->returnTransaction) ? $this->returnTransaction : null,
            'account' => isset($this->returnTransaction) ? new AccountResource($this->returnTransaction->cashbookAccount) : null,
            'returnDate' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}
