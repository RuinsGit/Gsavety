<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutCartSectionResource;
use App\Models\AboutCartSection;
use Illuminate\Http\Request;

class AboutCartSectionApiController extends Controller
{
    /**
     * Hakkımızda kart bölümü bilgisini getir
     *
     * @return \App\Http\Resources\AboutCartSectionResource
     */
    public function index()
    {
        $aboutCartSection = AboutCartSection::where('status', 1)->first();
        
        if (!$aboutCartSection) {
            return response()->json([
                'success' => false,
                'message' => 'Hakkımızda kart bölümü bilgisi bulunamadı.'
            ], 404);
        }
            
        return new AboutCartSectionResource($aboutCartSection);
    }
} 