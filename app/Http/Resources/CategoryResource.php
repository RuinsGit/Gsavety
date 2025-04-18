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
        $locale = app()->getLocale();
        
        return [
            'id' => $this->id,
            'name' => $this->{"name_" . $locale} ?? $this->name_az,
            'description' => $this->{"description_" . $locale} ?? $this->description_az,
            'slug' => [
                'az' => $this->slug_az,
                'en' => $this->slug_en,
                'ru' => $this->slug_ru,
            ],
            'image' => $this->image ? asset($this->image) : null,
            'status' => (bool) $this->status,
            'sort_order' => (int) $this->sort_order,
            'products' => $this->when($this->relationLoaded('products'), function() {
                return ProductResource::collection($this->products);
            }),
            'product_count' => $this->when($this->relationLoaded('products'), function() {
                return $this->products->count();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
