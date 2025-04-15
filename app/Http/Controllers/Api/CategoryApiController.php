<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * Tüm kategorileri listele
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $categories = Category::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
            
        return CategoryResource::collection($categories);
    }
    
    /**
     * Belirli bir kategorinin detayını getir
     *
     * @param  string  $id
     * @return \App\Http\Resources\CategoryResource
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        
        return new CategoryResource($category);
    }
    
    /**
     * Belirli bir kategoriye ait ürünleri getir
     *
     * @param  string  $categoryId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCategoryProducts($categoryId, Request $request)
    {
        $category = Category::findOrFail($categoryId);
        
        $query = Product::whereHas('categories', function($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
        
        // Status filtresi
        $query->where('status', 1);
        
        // Sıralama
        if ($request->has('sort_by')) {
            $sortDirection = $request->has('sort_dir') ? $request->sort_dir : 'asc';
            
            switch ($request->sort_by) {
                case 'price':
                    $query->orderBy('price', $sortDirection);
                    break;
                case 'name':
                    $query->orderBy('name_' . $request->locale ?? 'az', $sortDirection);
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('id', $sortDirection);
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        
        // Sayfalama
        $perPage = $request->has('per_page') ? (int)$request->per_page : 12;
        
        $products = $query->with(['categories'])
            ->paginate($perPage);
            
        return ProductResource::collection($products);
    }
} 