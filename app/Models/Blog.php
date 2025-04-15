<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'description_az',
        'description_en',
        'description_ru',
        'short_description_az',
        'short_description_en',
        'short_description_ru',
        'slug_az',
        'slug_en',
        'slug_ru',
        'image',
        'status',
        'published_at',
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'boolean',
    ];
    
    // Aksesörler
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
    
    public function getShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $column = "short_description_" . $locale;
        
        return $this->{$column};
    }
    
    public function getSlugAttribute()
    {
        $locale = app()->getLocale();
        $column = "slug_" . $locale;
        
        return $this->{$column};
    }

    // Scopelar
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }
}
