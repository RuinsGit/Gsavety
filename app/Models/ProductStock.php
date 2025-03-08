<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'product_color_id',
        'product_size_id',
        'quantity',
        'sku',
        'price',
        'discount_price',
        'status',
    ];
    
    // İlişkiler
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
    
    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }
}
