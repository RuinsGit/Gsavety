<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeCartSectionResource;
use App\Models\HomeCartSection;
use Illuminate\Http\Request;

class HomeCartSectionApiController extends Controller
{
    /**
     * Ana sayfa kart bölümlerini listele
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $cartSections = HomeCartSection::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
            
        return HomeCartSectionResource::collection($cartSections);
    }
    
    /**
     * Belirli bir kart bölümünün detayını getir
     *
     * @param  string  $id
     * @return \App\Http\Resources\HomeCartSectionResource
     */
    public function show($id)
    {
        $cartSection = HomeCartSection::findOrFail($id);
        
        return new HomeCartSectionResource($cartSection);
    }
} 