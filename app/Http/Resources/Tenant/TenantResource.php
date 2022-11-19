<?php

namespace App\Http\Resources\Tenant;

use App\Http\Resources\Plan\PlanResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = collect([
            'plan' => new PlanResource($this->plan),
        ]);

        if ($this->tenant_invoices) {
            $data->add([
                'tenant_invoices' => $this->tenant_invoices,
            ]);
        }

        return parent::toArray($request) + $data->toArray();
    }
}