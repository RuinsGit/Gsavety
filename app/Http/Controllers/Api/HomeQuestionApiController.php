<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeQuestionResource;
use App\Models\HomeQuestion;
use Illuminate\Http\Request;

class HomeQuestionApiController extends Controller
{
    /**
     * Tüm aktif soruları listele
     */
    public function index()
    {
        $questions = HomeQuestion::where('status', true)
            ->orderBy('order')
            ->get();
        
        return HomeQuestionResource::collection($questions);
    }

    /**
     * Belirli bir sorunun detaylarını getir
     */
    public function show($id)
    {
        try {
            $question = HomeQuestion::findOrFail($id);
            return new HomeQuestionResource($question);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sual tapılmadı'], 404);
        }
    }
} 