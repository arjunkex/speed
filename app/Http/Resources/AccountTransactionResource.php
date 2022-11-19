<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account' => new AccountResource($this->cashbookAccount),
            'reason' => $this->reason,
            'slug' => $this->slug,
            'amount' => round($this->amount, 2),
            'type' => (int) $this->type,
            'transactionDate' => date_format(date_create($this->transaction_date), 'Y-m-d'),
            'note' => $this->note,
            'status' => (int) $this->status,
            'user' => $this->user,
        ];
    }
}
