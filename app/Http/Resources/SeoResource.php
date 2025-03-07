<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'status' => (bool)$this->status,
        ];
    }
} 