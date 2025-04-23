<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_color_id',
        'product_size_id',
        'price',
        'quantity',
        'subtotal'
    ];
    
    /**
     * Sepet öğesinin ait olduğu sepet
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
    
    /**
     * Sepet öğesinin ürünü
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Sepet öğesinin rengi
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
    
    /**
     * Sepet öğesinin boyutu
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }
}
