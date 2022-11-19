<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CentralBillingHistoryResource extends JsonResource
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
            'amount' => $this['amount'],
            'currency' => $this['currency'],
            'description' => $this['description'],
            'net' => $this['net'],
            'type' => $this['type'],
            'created' => Carbon::createFromTimestamp($this['created'])->format('d-m-Y'),
            'status' => $this['status'],
            'stripeFee' => $this['fee_details'][0]['amount'],
            'stripeFeeDescription' => $this['fee_details'][0]['description'],

        ];
    }
}
