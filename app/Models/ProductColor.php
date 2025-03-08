<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'color_name_az',
        'color_name_en',
        'color_name_ru',
        'color_code',
        'color_image',
        'status',
        'sort_order',
    ];
    
    // Çok dilli özellikler için accessor'lar
    public function getColorNameAttribute()
    {
        return $this->{'color_name_' . app()->getLocale()};
    }
    
    // İlişkiler
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }
}
