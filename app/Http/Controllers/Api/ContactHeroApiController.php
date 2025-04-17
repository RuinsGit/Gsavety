<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactHeroResource;
use App\Models\ContactHero;
use Illuminate\Http\Request;

class ContactHeroApiController extends Controller
{
    /**
     * Əlaqə Hero məlumatlarını gətir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contactHeroes = ContactHero::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
        
        if ($contactHeroes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Əlaqə Hero məlumatları tapılmadı.'
            ], 404);
        }
            
        return ContactHeroResource::collection($contactHeroes);
    }
    
    /**
     * Müəyyən bir Əlaqə Hero məlumatını gətir
     *
     * @param  int  $id
     * @return \App\Http\Resources\ContactHeroResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $contactHero = ContactHero::where('status', 1)
            ->where('id', $id)
            ->first();
        
        if (!$contactHero) {
            return response()->json([
                'success' => false,
                'message' => 'Əlaqə Hero məlumatı tapılmadı.'
            ], 404);
        }
            
        return new ContactHeroResource($contactHero);
    }
} 