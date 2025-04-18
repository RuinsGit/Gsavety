<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        
        return [
            [
                'title' => $this->{"number_title_$locale"} ?? 'Əlaqə Nömrəsi',
                'value' => $this->number,
                'image' => $this->number_image ? asset($this->number_image) : null,
                'id' => 1,
            ],
            [
                'title' => $this->{"mail_title_$locale"} ?? 'Əlaqə Email',
                'value' => $this->mail,
                'image' => $this->mail_image ? asset($this->mail_image) : null,
                'id' => 2,
            ],
            [
                'title' => $this->{"address_title_$locale"} ?? 'Əlaqə Adresi',
                'value' => $this->address,
                'image' => $this->address_image ? asset($this->address_image) : null,
                'id' => 3,
            ],
            [
                'value' => $this->filial_description,
                'id' => 5,
            ]
        ];
    }
} 