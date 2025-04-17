<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutCenterCartResource;
use App\Models\AboutCenterCart;
use Illuminate\Http\Request;

class AboutCenterCartApiController extends Controller
{
    /**
     * Hakkımızda merkez kart bilgisini getir
     *
     * @return \App\Http\Resources\AboutCenterCartResource
     */
    public function index()
    {
        $aboutCenterCart = AboutCenterCart::first();
        
        if (!$aboutCenterCart) {
            return response()->json([
                'success' => false,
                'message' => 'Hakkımızda merkez kart bilgisi bulunamadı.'
            ], 404);
        }
            
        return new AboutCenterCartResource($aboutCenterCart);
    }
} 