<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'description_az',
        'description_en',
        'description_ru',
        'status',
        'order'
    ];

    public function getTitleAttribute()
    {
        return $this->{'title_' . app()->getLocale()};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . app()->getLocale()};
    }
    
    

    protected $casts = [
        'status' => 'boolean'
    ];
} 