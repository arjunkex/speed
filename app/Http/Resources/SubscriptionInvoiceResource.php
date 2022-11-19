<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'id'    => $this->id,
        //     'date'  => $this->date()->toFormattedDateString(),
        //     'total' => $this->total(),
        // ];

        return parent::toArray($request);
    }
}
