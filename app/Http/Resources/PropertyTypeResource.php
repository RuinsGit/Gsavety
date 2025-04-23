<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // id'yi index'e göre otomatik oluştur
        static $typeCounter = 0;
        $typeCounter++;
        
        return [
            'id' => $typeCounter,
            'filter_type' => $this['type_name'] ?? 'Digər',
            'properties' => collect($this['properties'] ?? [])->map(function($property, $index) {
                static $propertyId = 100;
                $propertyId++;
                
                return [
                    'id' => $propertyId,
                    'value' => $property['name'] ?? '',
                ];
            })->values()->all(),
        ];
    }
}
