<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $fillable = [
        'key',
        'meta_title_az',
        'meta_title_en',
        'meta_title_ru',
        'meta_description_az',
        'meta_description_en',
        'meta_description_ru',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function getMetaTitleAttribute()
    {
        return $this->{'meta_title_' . app()->getLocale()};
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->{'meta_description_' . app()->getLocale()};
    }
}
