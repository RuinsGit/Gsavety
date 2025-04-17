<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutFeaturedBoxResource;
use App\Models\AboutFeaturedBox;
use Illuminate\Http\Request;

class AboutFeaturedBoxApiController extends Controller
{
    /**
     * Hakkımızda kutu bölümü bilgilerini getir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $aboutFeaturedBoxes = AboutFeaturedBox::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
        
        if ($aboutFeaturedBoxes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Hakkımızda kutu bilgileri bulunamadı.'
            ], 404);
        }
            
        return AboutFeaturedBoxResource::collection($aboutFeaturedBoxes);
    }
    
    /**
     * Belirli bir kutu bilgisini getir
     *
     * @param int $id
     * @return \App\Http\Resources\AboutFeaturedBoxResource
     */
    public function show($id)
    {
        $aboutFeaturedBox = AboutFeaturedBox::where('status', 1)->findOrFail($id);
        
        return new AboutFeaturedBoxResource($aboutFeaturedBox);
    }
} 