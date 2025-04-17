<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeFollowResource;
use App\Models\HomeFollow;
use Illuminate\Http\Request;

class HomeFollowApiController extends Controller
{
    /**
     * Ana sayfa sosyal medya takip bölümünü listele
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $follow = HomeFollow::where('status', 1)
            ->orderBy('order', 'asc')
            ->first(); // sadece bir tane al
    
        return new HomeFollowResource($follow);
    }
    
    
    /**
     * Belirli bir sosyal medya takip bölümünün detayını getir
     *
     * @param  string  $id
     * @return \App\Http\Resources\HomeFollowResource
     */
    public function show($id)
    {
        $follow = HomeFollow::findOrFail($id);
        
        return new HomeFollowResource($follow);
    }
} 