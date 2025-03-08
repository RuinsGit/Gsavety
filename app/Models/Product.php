<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'reference',
        'sku',
        'name_az',
        'name_en',
        'name_ru',
        'description_az',
        'description_en',
        'description_ru',
        'price',
        'discount_price',
        'is_featured',
        'status',
        'main_image',
        'meta_title_az',
        'meta_title_en',
        'meta_title_ru',
        'meta_description_az',
        'meta_description_en',
        'meta_description_ru',
        'slug_az',
        'slug_en',
        'slug_ru',
    ];
    
  
    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }
    
    public function getDescriptionAttribute()
    {
        return $this->{'description_' . app()->getLocale()};
    }
    
    public function getMetaTitleAttribute()
    {
        return $this->{'meta_title_' . app()->getLocale()};
    }
    
    public function getMetaDescriptionAttribute()
    {
        return $this->{'meta_description_' . app()->getLocale()};
    }
    
    public function getSlugAttribute()
    {
        return $this->{'slug_' . app()->getLocale()};
    }
    
 
    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }
    
    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }
    
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }
    
 
    public function getMainImageAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        $mainImage = $this->images()->where('is_main', true)->first();
        return $mainImage ? $mainImage->image_path : null;
    }
}
