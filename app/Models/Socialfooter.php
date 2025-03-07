<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socialfooter extends Model
{
    protected $table = 'social_footer';
    
    protected $fillable = [
        'image',
        'link',
        'order',
        'status'
    ];
}