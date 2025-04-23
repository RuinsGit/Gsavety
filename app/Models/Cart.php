<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'item_count',
        'is_active'
    ];
    
    /**
     * Sepet ile ilişkili kullanıcıyı döndürür
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Sepet öğelerini döndürür
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
