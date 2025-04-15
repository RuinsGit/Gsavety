<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
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
            'product_id' => $this->product_id,
            'name' => [
                'az' => $this->color_name_az,
                'en' => $this->color_name_en,
                'ru' => $this->color_name_ru,
            ],
            'color_code' => $this->color_code,
            'color_image' => $this->color_image ? asset($this->color_image) : null,
            'status' => (bool) $this->status,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 