<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'number',
        'number_image',
        'mail',
        'mail_image',
        'address_az',
        'address_en',
        'address_ru',
        'address_image',
        'filial_description'
    ];

    public function getAddressAttribute()
    {
        return $this->{'address_' . app()->getLocale()};
    }
} 