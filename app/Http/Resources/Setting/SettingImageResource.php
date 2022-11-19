<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingImageResource extends JsonResource
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
            'image' => global_asset($this->image),
            'title' => $this->title,
            'description' => $this->description,
            'name' => $this->name,
            'image_align_left' => $this->image_align_left,
            'points' => $this->points ? json_decode($this->points) : [],
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
