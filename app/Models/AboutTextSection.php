<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutTextSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title1_az',
        'title1_en',
        'title1_ru',
        'description1_az',
        'description1_en',
        'description1_ru',
        'title2_az',
        'title2_en',
        'title2_ru',
        'description2_az',
        'description2_en',
        'description2_ru',
        'title3_az',
        'title3_en',
        'title3_ru',
        'description3_az',
        'description3_en',
        'description3_ru',
        'status'
    ];

    // Aksesörler - Başlık 1
    public function getTitle1Attribute()
    {
        $locale = app()->getLocale();
        $column = "title1_" . $locale;
        
        return $this->{$column};
    }

    // Aksesörler - Açıklama 1
    public function getDescription1Attribute()
    {
        $locale = app()->getLocale();
        $column = "description1_" . $locale;
        
        return $this->{$column};
    }

    // Aksesörler - Başlık 2
    public function getTitle2Attribute()
    {
        $locale = app()->getLocale();
        $column = "title2_" . $locale;
        
        return $this->{$column};
    }

    // Aksesörler - Açıklama 2
    public function getDescription2Attribute()
    {
        $locale = app()->getLocale();
        $column = "description2_" . $locale;
        
        return $this->{$column};
    }

    // Aksesörler - Başlık 3
    public function getTitle3Attribute()
    {
        $locale = app()->getLocale();
        $column = "title3_" . $locale;
        
        return $this->{$column};
    }

    // Aksesörler - Açıklama 3
    public function getDescription3Attribute()
    {
        $locale = app()->getLocale();
        $column = "description3_" . $locale;
        
        return $this->{$column};
    }

    // Scopelar
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
} 