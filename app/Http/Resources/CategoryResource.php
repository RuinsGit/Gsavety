<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => [
                'az' => $this->name_az,
                'en' => $this->name_en,
                'ru' => $this->name_ru,
            ],
            'description' => [
                'az' => $this->description_az,
                'en' => $this->description_en,
                'ru' => $this->description_ru,
            ],
            'slug' => [
                'az' => $this->slug_az,
                'en' => $this->slug_en,
                'ru' => $this->slug_ru,
            ],
            'image' => $this->image ? asset($this->image) : null,
            'status' => (bool) $this->status,
            'sort_order' => $this->sort_order,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'product_count' => $this->when($this->relationLoaded('products'), function() {
                return $this->products->count();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
