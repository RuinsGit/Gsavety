<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoScript extends Model
{
    use HasFactory;

    protected $fillable = [
        'script_content',
        'status'
    ];
}
