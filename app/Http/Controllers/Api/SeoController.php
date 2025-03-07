<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeoResource;
use App\Models\Seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        $seos = Seo::where('status', true)->get();
        return SeoResource::collection($seos);
    }

    public function show($key)
    {
        $seo = Seo::where('key', $key)
                  ->where('status', true)
                  ->first();

        if (!$seo) {
            return response()->json([
                'message' => 'SEO məlumatı tapılmadı'
            ], 404);
        }

        return new SeoResource($seo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:seos',
            'meta_title_az' => 'required',
            'meta_title_en' => 'required',
            'meta_title_ru' => 'required',
            'meta_description_az' => 'required',
            'meta_description_en' => 'required',
            'meta_description_ru' => 'required',
        ]);

        $seo = Seo::create($request->all());
        return new SeoResource($seo);
    }

    public function update(Request $request, $id)
    {
        $seo = Seo::findOrFail($id);

        $request->validate([
            'key' => 'required|unique:seos,key,' . $id,
            'meta_title_az' => 'required',
            'meta_title_en' => 'required',
            'meta_title_ru' => 'required',
            'meta_description_az' => 'required',
            'meta_description_en' => 'required',
            'meta_description_ru' => 'required',
        ]);

        $seo->update($request->all());
        return new SeoResource($seo);
    }

    public function destroy($id)
    {
        $seo = Seo::findOrFail($id);
        $seo->delete();

        return response()->json([
            'message' => 'SEO məlumatı uğurla silindi'
        ]);
    }
} 