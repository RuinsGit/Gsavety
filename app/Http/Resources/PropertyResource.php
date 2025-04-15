<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'property_type' => $this->property_type,
            'name' => [
                'az' => $this->property_name_az,
                'en' => $this->property_name_en,
                'ru' => $this->property_name_ru,
            ],
            'value' => [
                'az' => $this->property_value_az,
                'en' => $this->property_value_en,
                'ru' => $this->property_value_ru,
            ],
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 