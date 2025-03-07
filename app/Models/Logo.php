<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    // Toplu atama için izin verilen alanlar
    protected $fillable = [
        'logo_1_image',
        'logo_2_image',
        'logo_alt1_az',
        'logo_alt1_en',
        'logo_alt1_ru',
        'logo_alt2_az',
        'logo_alt2_en',
        'logo_alt2_ru',
        'logo_title1_az',
        'logo_title1_en',
        'logo_title1_ru',
        'logo_title2_az',
        'logo_title2_en',
        'logo_title2_ru',
    ];
    public function getLogoAlt1Attribute()
    {
        return $this->{'logo_alt1_' . app()->getLocale()};
    }
    public function getLogoAlt2Attribute()
    {
        return $this->{'logo_alt2_' . app()->getLocale()};
    }
    public function getLogoTitle1Attribute()
    {
        return $this->{'logo_title1_' . app()->getLocale()};
    }
    public function getLogoTitle2Attribute()
    {
        return $this->{'logo_title2_' . app()->getLocale()};
    }
}
