<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogBannerResource;
use App\Models\BlogBanner;
use Illuminate\Http\Request;

class BlogBannerApiController extends Controller
{
    /**
     * Blog banner bilgilerini getir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $banner = BlogBanner::all()->first(); // tüm kayıtları alıp ilkini seçer
    
        if (!$banner) {
            return response()->json(['message' => 'Blog banner bilgisi bulunamadı.'], 404);
        }
    
        return new BlogBannerResource($banner);
    }
    
    /**
     * Belirli bir blog banner detayını getir
     *
     * @param  int  $id
     * @return \App\Http\Resources\BlogBannerResource
     */
    public function show($id)
    {
        $banner = BlogBanner::where('status', 1)->findOrFail($id);
        
        return new BlogBannerResource($banner);
    }
} 