<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeFeaturedBoxResource;
use App\Models\HomeFeaturedBox;
use Illuminate\Http\Request;

class HomeFeaturedBoxApiController extends Controller
{
    /**
     * Ana sayfa öne çıkan kutularını listele
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $featuredBoxes = HomeFeaturedBox::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
            
        return HomeFeaturedBoxResource::collection($featuredBoxes);
    }
    
    /**
     * Belirli bir öne çıkan kutunun detayını getir
     *
     * @param  string  $id
     * @return \App\Http\Resources\HomeFeaturedBoxResource
     */
    public function show($id)
    {
        $featuredBox = HomeFeaturedBox::findOrFail($id);
        
        return new HomeFeaturedBoxResource($featuredBox);
    }
} 