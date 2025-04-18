<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductBannerResource;
use App\Models\ProductBanner;
use Illuminate\Http\Request;

class ProductBannerApiController extends Controller
{
    /**
     * Product banner bilgilerini getir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $banner = ProductBanner::all()->first(); // tüm kayıtları alıp ilkini seçer
    
        if (!$banner) {
            return response()->json(['message' => 'Product banner bilgisi bulunamadı.'], 404);
        }
    
        return new ProductBannerResource($banner);
    }
    
    /**
     * Belirli bir product banner detayını getir
     *
     * @param  int  $id
     * @return \App\Http\Resources\ProductBannerResource
     */
    public function show($id)
    {
        $banner = ProductBanner::where('status', 1)->findOrFail($id);
        
        return new ProductBannerResource($banner);
    }
} 