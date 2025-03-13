<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeFeaturedBox extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'image',
        'status',
        'order'
    ];
    
    // Aksesörler
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $column = "title_" . $locale;
        
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
