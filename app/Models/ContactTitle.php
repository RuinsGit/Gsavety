<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactTitle extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'special_title_az',
        'special_title_en',
        'special_title_ru',
        'description_az',
        'description_en',
        'description_ru',
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
    
    // Aksesörler - Özel Başlık
    public function getSpecialTitleAttribute()
    {
        $locale = app()->getLocale();
        $column = "special_title_" . $locale;
        
        return $this->{$column};
    }
    
    // Aksesörler - Açıklama
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $column = "description_" . $locale;
        
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