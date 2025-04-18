<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'number',
        'number_image',
        'number_title_az',
        'number_title_en',
        'number_title_ru',
        'mail',
        'mail_image',
        'mail_title_az',
        'mail_title_en',
        'mail_title_ru',
        'address_az',
        'address_en',
        'address_ru',
        'address_image',
        'address_title_az',
        'address_title_en',
        'address_title_ru',
        'filial_description'
    ];

    public function getAddressAttribute()
    {
        return $this->{'address_' . app()->getLocale()};
    }

    public function getNumberTitleAttribute()
    {
        return $this->{'number_title_' . app()->getLocale()};
    }

    public function getMailTitleAttribute()
    {
        return $this->{'mail_title_' . app()->getLocale()};
    }

    public function getAddressTitleAttribute()
    {
        return $this->{'address_title_' . app()->getLocale()};
    }
} 