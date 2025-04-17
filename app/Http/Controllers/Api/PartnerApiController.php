<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerApiController extends Controller
{
    /**
     * Partner bilgilerini getir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $partners = Partner::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
        
        if ($partners->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Partner bilgileri bulunamadı.'
            ], 404);
        }
            
        return PartnerResource::collection($partners);
    }
    
    /**
     * Belirli bir partner bilgisini getir
     *
     * @param int $id
     * @return \App\Http\Resources\PartnerResource
     */
    public function show($id)
    {
        $partner = Partner::where('status', 1)->findOrFail($id);
        
        return new PartnerResource($partner);
    }
} 