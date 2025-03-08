<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'size_name_az',
        'size_name_en',
        'size_name_ru',
        'size_value',
        'status',
        'sort_order',
    ];
    
   
    public function getSizeNameAttribute()
    {
        return $this->{'size_name_' . app()->getLocale()};
    }
    
  
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }
}
