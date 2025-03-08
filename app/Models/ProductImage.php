<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'product_color_id',
        'image_path',
        'alt_text_az',
        'alt_text_en',
        'alt_text_ru',
        'is_main',
        'status',
        'sort_order',
    ];
    
  
    public function getAltTextAttribute()
    {
        return $this->{'alt_text_' . app()->getLocale()};
    }
    
    // İlişkiler
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
}
