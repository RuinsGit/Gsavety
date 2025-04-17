<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHero extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'image',
        'image_alt_az',
        'image_alt_en',
        'image_alt_ru',
        'status',
        'order'
    ];
    
    // Aksesörler - Başlık
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $column = "title_" . $locale;
        
        return $this->{$column};
    }
    
    // Aksesörler - Resim Alt
    public function getImageAltAttribute()
    {
        $locale = app()->getLocale();
        $column = "image_alt_" . $locale;
        
        return $this->{$column};
    }
    
    // Scopelar
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
} 