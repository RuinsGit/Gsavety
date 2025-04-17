<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'reference' => $this->reference,
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'main_image' => $this->main_image ? asset($this->main_image) : null,
            'is_featured' => (bool) $this->is_featured,
            'status' => (bool) $this->status,
            'slug' => [
                'az' => $this->slug_az,
                'en' => $this->slug_en,
                'ru' => $this->slug_ru,
            ],
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
            ],
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'properties' => PropertyResource::collection($this->whenLoaded('properties')),
            'colors' => ColorResource::collection($this->whenLoaded('colors')),
            'sizes' => SizeResource::collection($this->whenLoaded('sizes')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'stocks' => StockResource::collection($this->whenLoaded('stocks')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
