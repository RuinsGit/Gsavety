<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartnerBanner;
use Illuminate\Http\Request;

class PartnerBannerApiController extends Controller
{
    /**
     * Tüm partner banner'ları listele
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $partnerBanners = PartnerBanner::active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $partnerBanners->map(function($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'image' => $banner->image ? url($banner->image) : null,
                    'order' => $banner->order,
                ];
            })
        ]);
    }

    /**
     * Belirli bir partner banner'ı göster
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $banner = PartnerBanner::active()->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $banner->id,
                'title' => $banner->title,
                'image' => $banner->image ? url($banner->image) : null,
                'order' => $banner->order,
            ]
        ]);
    }
} 