<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeHeroResource;
use App\Models\HomeHero;
use Illuminate\Http\Request;

class HomeHeroApiController extends Controller
{
    /**
     * Ana sayfa hero görsellerini listele
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $heroes = HomeHero::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
            
        return HomeHeroResource::collection($heroes);
    }
    
    /**
     * Belirli bir hero görselinin detayını getir
     *
     * @param  string  $id
     * @return \App\Http\Resources\HomeHeroResource
     */
    public function show($id)
    {
        $hero = HomeHero::findOrFail($id);
        
        return new HomeHeroResource($hero);
    }
} 