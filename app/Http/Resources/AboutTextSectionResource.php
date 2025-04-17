<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutTextSectionResource extends JsonResource
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
            'section_first' => [
                'id' => $this->id,
                'title' => $this->title1,
                'description' => $this->description1,
            ],
            'section_second' => [
                'id' => $this->id +1,
                'title' => $this->title2,
                'description' => $this->description2,
            ],
            'section_third' => [
                'id' => $this->id +2,
                'title' => $this->title3,
                'description' => $this->description3,
            ],
            // 'status' => (bool) $this->status,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
} 