<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactTitleResource;
use App\Models\ContactTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ContactTitleApiController extends Controller
{
    /**
     * Əlaqə Başlıqları məlumatlarını gətir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contactTitle = ContactTitle::where('status', 1)
            ->orderBy('order', 'asc')
            ->first(); // sadece bir tane al
    
        return new ContactTitleResource($contactTitle);
    }
    
    /**
     * Müəyyən bir Əlaqə Başlığı məlumatını gətir
     *
     * @param  int  $id
     * @return \App\Http\Resources\ContactTitleResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $contactTitle = ContactTitle::where('status', 1)
            ->where('id', $id)
            ->first();
        
        if (!$contactTitle) {
            return response()->json([
                'success' => false,
                'message' => 'Əlaqə başlığı məlumatı tapılmadı.'
            ], 404);
        }
            
        return new ContactTitleResource($contactTitle);
    }
} 