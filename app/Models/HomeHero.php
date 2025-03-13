<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeHero extends Model
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
    
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
   
    public function getTitleAttribute()
    {
        return $this->{'title_' . app()->getLocale()};
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
