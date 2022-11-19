<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Feature\FeatureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'api_id' => $this->api_id,
            'image' => global_asset($this->image),
            'name' => $this->name,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'interval' => $this->interval,
            'description' => $this->description,
            'status' => $this->status,
            'product_id' => $this->product_id,
            'limit_clients' => $this->limit_clients,
            'limit_suppliers' => $this->limit_suppliers,
            'limit_employees' => $this->limit_employees,
            'limit_domains' => $this->limit_domains,
            'limit_purchases' => $this->limit_purchases,
            'limit_invoices' => $this->limit_invoices,
            'features' => FeatureResource::collection($this->features()->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
