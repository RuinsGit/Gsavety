<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranslationManageResource;
use App\Models\TranslationManage;
use Illuminate\Http\Request;

class TranslationManageController extends Controller
{
    public function index()
    {
        $translations = TranslationManage::where('status', 1)->get();
        return TranslationManageResource::collection($translations);
    }

    public function show($id)
    {
        try {
            $translation = TranslationManage::findOrFail($id);
            return new TranslationManageResource($translation);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Translation not found'], 404);
        }
    }

    public function getByKey($key)
    {
        $translation = TranslationManage::where('key', $key)
            ->where('status', 1)
            ->first();
            
        if (!$translation) {
            return response()->json(['message' => 'Translation not found'], 404);
        }
        
        return new TranslationManageResource($translation);
    }

    public function getByGroup($group)
    {
        $translations = TranslationManage::where('group', $group)
            ->where('status', 1)
            ->get();
            
        if ($translations->isEmpty()) {
            return response()->json(['message' => 'No translations found for this group'], 404);
        }
        
        return TranslationManageResource::collection($translations);
    }
} 