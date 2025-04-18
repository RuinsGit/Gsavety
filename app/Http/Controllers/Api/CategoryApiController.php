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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Category::where('status', 1)->orderBy('id', 'asc');
        
        // İlişkili ürünleri yükle (varsayılan olarak her zaman yükle)
        $loadProducts = $request->has('with_products') ? (bool)$request->with_products : true;
        
        if ($loadProducts) {
            $limit = $request->has('product_limit') ? (int)$request->product_limit : 10;
            
            $query->with(['products' => function($q) use ($limit) {
                $q->where('status', 1)
                  ->orderBy('id', 'desc')
                  ->limit($limit);
            }]);
        }
            
        $categories = $query->get();
        
        return CategoryResource::collection($categories);
    }
    
    /**
     * Belirli bir kategorinin detayını getir
     *
     * @param  string  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\CategoryResource
     */
    public function show($id, Request $request)
    {
        $query = Category::where('id', $id);
        
        // İlişkili ürünleri yükle (varsayılan olarak her zaman yükle)
        $loadProducts = $request->has('with_products') ? (bool)$request->with_products : true;
        
        if ($loadProducts) {
            $limit = $request->has('product_limit') ? (int)$request->product_limit : 10;
            
            $query->with(['products' => function($q) use ($limit) {
                $q->where('status', 1)
                  ->orderBy('id', 'desc')
                  ->limit($limit);
            }]);
        }
        
        $category = $query->firstOrFail();
        
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
        
        // İlişkili ürün bilgilerini yükle
        $query->with([
            'categories',
            'properties' => function($q) {
                $q->with('values');
            },
            'colors' => function($q) {
                $q->where('status', 1)->orderBy('sort_order', 'asc');
            },
            'sizes' => function($q) {
                $q->where('status', 1)->orderBy('sort_order', 'asc');
            },
            'stocks' => function($q) {
                $q->where('status', 1)->with(['color', 'size']);
            },
            'images'
        ]);
        
        // Sayfalama
        $perPage = $request->has('per_page') ? (int)$request->per_page : 12;
        
        $products = $query->paginate($perPage);
            
        return ProductResource::collection($products);
    }
} 