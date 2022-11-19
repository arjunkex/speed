<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DomainRequestResource extends JsonResource
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
            'tenant' => $this->tenant,
            'requested_domain' => $this->requested_domain,
            'status' => $this->status,
            'modifiedBy' => $this->modifiedBy,
            'modified_at' => $this->modified_at,
            'status_html' => $this->getStatusHtml(),
        ];
    }
}
