<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\About;
use Illuminate\Http\Request;

class AboutApiController extends Controller
{
    /**
     * Hakkımızda bilgisini getir
     *
     * @return \App\Http\Resources\AboutResource
     */
    public function index()
    {
        $about = About::where('status', 1)->first();
        
        if (!$about) {
            return response()->json([
                'success' => false,
                'message' => 'Hakkımızda bilgisi bulunamadı.'
            ], 404);
        }
            
        return new AboutResource($about);
    }
}
