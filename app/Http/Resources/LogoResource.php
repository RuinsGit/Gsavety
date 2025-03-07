<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'header_logo' => asset($this->logo_1_image),
            'footer_logo' => asset($this->logo_2_image),
            'header_alt' => $this->logo_alt1,
            'footer_alt' => $this->logo_alt2,
            'header_title' => $this->logo_title1,
            'footer_title' => $this->logo_title2,
            
           
        ];
    }
} 