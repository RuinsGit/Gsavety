<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutTextSectionResource;
use App\Models\AboutTextSection;
use Illuminate\Http\Request;

class AboutTextSectionApiController extends Controller
{
    /**
     * Hakkımızda metin bölümlerini getir
     *
     * @return \App\Http\Resources\AboutTextSectionResource
     */
    public function index()
    {
        $aboutTextSection = AboutTextSection::where('status', 1)->first();
        
        if (!$aboutTextSection) {
            return response()->json([
                'success' => false,
                'message' => 'Hakkımızda metin bölümleri bulunamadı.'
            ], 404);
        }
            
        return new AboutTextSectionResource($aboutTextSection);
    }
} 