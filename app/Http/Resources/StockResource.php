<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'product_color_id' => $this->product_color_id,
            'product_size_id' => $this->product_size_id,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'status' => (bool) $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'color' => $this->whenLoaded('color', function() {
                return [
                    'id' => $this->color->id,
                    'name' => [
                        'az' => $this->color->color_name_az,
                        'en' => $this->color->color_name_en,
                        'ru' => $this->color->color_name_ru,
                    ]
                ];
            }),
            'size' => $this->whenLoaded('size', function() {
                return [
                    'id' => $this->size->id,
                    'name' => [
                        'az' => $this->size->size_name_az,
                        'en' => $this->size->size_name_en,
                        'ru' => $this->size->size_name_ru,
                    ],
                    'value' => $this->size->size_value
                ];
            }),
        ];
    }
} 