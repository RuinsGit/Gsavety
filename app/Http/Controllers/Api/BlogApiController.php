<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    /**
     * Blog yazılarını listele
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Blog::where('status', 1)
            ->published()
            ->orderBy('published_at', 'desc');
            
        // Sayfalama
        $perPage = $request->has('per_page') ? (int)$request->per_page : 10;
        
        $blogs = $query->paginate($perPage);
        
        return BlogResource::collection($blogs);
    }
    
    /**
     * Belirli bir blog yazısının detayını getir
     *
     * @param  int  $id
     * @return \App\Http\Resources\BlogResource
     */
    public function show($id)
    {
        $blog = Blog::where('status', 1)
            ->published()
            ->findOrFail($id);
        
        return new BlogResource($blog);
    }
    
    /**
     * Slug ile blog yazısı detayını getir
     *
     * @param  string  $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\BlogResource
     */
    public function showBySlug($slug, Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $slugColumn = 'slug_' . $locale;
        
        $blog = Blog::where('status', 1)
            ->published()
            ->where($slugColumn, $slug)
            ->firstOrFail();
        
        return new BlogResource($blog);
    }
} 