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
        $locale = app()->getLocale();
        
        $array = [
            'id' => $this->id,
            'reference' => $this->reference,
            'sku' => $this->sku,
            'name' => $this->{"name_" . $locale} ?? $this->name_az,
            'description' => $this->{"description_" . $locale} ?? $this->description_az,
            'price' => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        
        // İlişkili verileri ekleyelim
        if ($this->relationLoaded('categories')) {
            $array['categories'] = CategoryResource::collection($this->categories);
        }
        
        if ($this->relationLoaded('properties')) {
            $array['properties'] = $this->properties->map(function($property) use ($locale) {
                return [
                    'id' => $property->id,
                    'name' => $property->{"property_name_" . $locale} ?? $property->property_name_az,
                    'value' => $property->{"property_value_" . $locale} ?? $property->property_value_az,
                    'property_type' => $property->property_type,
                    'sort_order' => $property->sort_order ?? 0
                ];
            });
        }
        
        if ($this->relationLoaded('colors')) {
            $array['colors'] = $this->colors->map(function($color) use ($locale) {
                return [
                    'id' => $color->id,
                    'name' => $color->{"color_name_" . $locale} ?? $color->color_name_az,
                    'color_code' => $color->color_code,
                    // 'color_image' => $color->color_image ? asset($color->color_image) : null,
                    'status' => (bool) $color->status,
                    'sort_order' => $color->sort_order
                ];
            });
        }
        
        if ($this->relationLoaded('sizes')) {
            $array['sizes'] = $this->sizes->map(function($size) use ($locale) {
                return [
                    'id' => $size->id,
                    'name' => $size->{"size_name_" . $locale} ?? $size->size_name_az,
                    'value' => $size->size_value,
                    'status' => (bool) $size->status,
                    'sort_order' => $size->sort_order
                ];
            });
        }
        
        if ($this->relationLoaded('stocks')) {
            // Stok bilgileri içinde renk ve boyut detayları
            $array['stocks'] = $this->stocks->map(function($stock) use ($locale) {
                return [
                    'id' => $stock->id,
                    'quantity' => (int) $stock->quantity,
                    'status' => (bool) $stock->status,
                    'color' => $stock->color ? [
                        'id' => $stock->color->id,
                        'name' => $stock->color->{"color_name_" . $locale} ?? $stock->color->color_name_az,
                        'color_code' => $stock->color->color_code,
                        // 'color_image' => $stock->color->color_image ? asset($stock->color->color_image) : null,
                    ] : null,
                    'size' => $stock->size ? [
                        'id' => $stock->size->id,
                        'name' => $stock->size->{"size_name_" . $locale} ?? $stock->size->size_name_az,
                        'value' => $stock->size->size_value,
                    ] : null,
                ];
            });
        }
        
        if ($this->relationLoaded('images')) {
            $array['images'] = $this->images->map(function($image) {
                return [
                    'id' => $image->id,
                    'image' => $image->image_path ? url($image->image_path) : null,
                    'alt' => $image->alt_text ?? '',
                    'sort_order' => $image->sort_order ?? 0
                ];
            });
        }
        
        return $array;
    }
}
