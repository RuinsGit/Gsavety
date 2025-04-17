<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceHeroResource;
use App\Models\ServiceHero;
use Illuminate\Http\Request;

class ServiceHeroApiController extends Controller
{
    /**
     * Servis Hero bilgilerini getir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $serviceHeroes = ServiceHero::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
        
        if ($serviceHeroes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Servis Hero məlumatları tapılmadı.'
            ], 404);
        }
            
        return ServiceHeroResource::collection($serviceHeroes);
    }
    
    /**
     * Belirli bir Servis Hero bilgisini getir
     *
     * @param  int  $id
     * @return \App\Http\Resources\ServiceHeroResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $serviceHero = ServiceHero::where('status', 1)
            ->where('id', $id)
            ->first();
        
        if (!$serviceHero) {
            return response()->json([
                'success' => false,
                'message' => 'Servis Hero məlumatı tapılmadı.'
            ], 404);
        }
            
        return new ServiceHeroResource($serviceHero);
    }
} 