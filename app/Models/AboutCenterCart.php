<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutCenterCart extends Model
{
    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'description_az',
        'description_en',
        'description_ru',
        'image'
    ];

    
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $column = "title_" . $locale;
        
        return $this->{$column};

    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $column = "description_" . $locale;
        
        return $this->{$column};
    }
} 