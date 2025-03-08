<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'property_name_az',
        'property_name_en',
        'property_name_ru',
        'property_value_az',
        'property_value_en',
        'property_value_ru',
        'property_type',
        'sort_order',
    ];
    
   
    public function getPropertyNameAttribute()
    {
        return $this->{'property_name_' . app()->getLocale()};
    }
    
    public function getPropertyValueAttribute()
    {
        return $this->{'property_value_' . app()->getLocale()};
    }
    
  
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
