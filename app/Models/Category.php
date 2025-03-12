<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'parent_id',
        'status',
        'order',
        'name_az',
        'name_en',
        'name_ru',
        'description_az',
        'description_en',
        'description_ru',
        'slug_az',
        'slug_en',
        'slug_ru',
        'image'
    ];
    
    // Şu anki dil için ad
    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }
    
    // Şu anki dil için açıklama
    public function getDescriptionAttribute()
    {
        return $this->{'description_' . app()->getLocale()};
    }
    
    // Şu anki dil için slug
    public function getSlugAttribute()
    {
        return $this->{'slug_' . app()->getLocale()};
    }
    
    // Alt kategoriler
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    // Üst kategori
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    // Bu kategoriye ait ürünler
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
    
    // Yeni kayıt için slug oluşturma
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug_az)) {
                $category->slug_az = Str::slug($category->name_az);
            }
            
            if (!empty($category->name_en) && empty($category->slug_en)) {
                $category->slug_en = Str::slug($category->name_en);
            }
            
            if (!empty($category->name_ru) && empty($category->slug_ru)) {
                $category->slug_ru = Str::slug($category->name_ru);
            }
        });
    }
}
