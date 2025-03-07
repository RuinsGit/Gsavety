<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogoResource;
use App\Models\Logo;
use Illuminate\Http\Request;

class LogoApiController extends Controller
{
    public function index()
    {
        $logos = Logo::all();
        return LogoResource::collection($logos);
    }

    public function show($id)
    {
        try {
            $logo = Logo::findOrFail($id);
            return new LogoResource($logo);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logo not found'], 404);
        }
    }

    public function getByKey($key)
    {
        $logo = Logo::where('key', $key)->first();
        
        if (!$logo) {
            return response()->json(['message' => 'Logo not found'], 404);
        }
        
        return new LogoResource($logo);
    }

    public function getByGroup($group)
    {
        $logos = Logo::where('group', $group)->get();
        
        if ($logos->isEmpty()) {
            return response()->json(['message' => 'No logos found for this group'], 404);
        }
        
        return LogoResource::collection($logos);
    }
}
