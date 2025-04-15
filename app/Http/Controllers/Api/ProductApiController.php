<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ColorResource;
use App\Http\Resources\SizeResource;
use App\Http\Resources\StockResource;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Tüm ürünleri listele (filtreleme ve sıralama destekli)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Kategori filtresi
        if ($request->has('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        
        // Durum filtresi
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Öne çıkan ürün filtresi
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }
        
        // Fiyat aralığı filtresi
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
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
        
        $products = $query->with(['categories'])->paginate($perPage);
        
        return ProductResource::collection($products);
    }
    
    /**
     * Öne çıkan ürünleri getir
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function featured()
    {
        $products = Product::where('status', 1)
            ->where('is_featured', 1)
            ->with(['categories'])
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();
            
        return ProductResource::collection($products);
    }
    
    /**
     * Ürün arama
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $locale = $request->input('locale', 'az');
        
        if (empty($query)) {
            return response()->json(['message' => 'Arama terimi girmelisiniz'], 400);
        }
        
        $products = Product::where('status', 1)
            ->where(function($q) use ($query, $locale) {
                $q->where('name_' . $locale, 'like', '%' . $query . '%')
                  ->orWhere('description_' . $locale, 'like', '%' . $query . '%')
                  ->orWhere('sku', 'like', '%' . $query . '%')
                  ->orWhere('reference', 'like', '%' . $query . '%');
            })
            ->with(['categories'])
            ->paginate(12);
            
        return ProductResource::collection($products);
    }
    
    /**
     * Belirli bir ürünün detayını getir
     *
     * @param  string  $id
     * @return \App\Http\Resources\ProductResource
     */
    public function show($id)
    {
        $product = Product::with([
            'categories',
            'properties',
            'colors',
            'sizes',
            'images',
            'stocks'
        ])->findOrFail($id);
        
        return new ProductResource($product);
    }
    
    /**
     * Belirli bir ürünün renklerini getir
     *
     * @param  string  $productId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductColors($productId)
    {
        $colors = ProductColor::where('product_id', $productId)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
            
        return ColorResource::collection($colors);
    }
    
    /**
     * Belirli bir ürünün boyutlarını getir
     *
     * @param  string  $productId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductSizes($productId)
    {
        $sizes = ProductSize::where('product_id', $productId)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
            
        return SizeResource::collection($sizes);
    }
    
    /**
     * Belirli bir ürünün stok durumunu getir
     *
     * @param  string  $productId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductStocks($productId)
    {
        $stocks = ProductStock::where('product_id', $productId)
            ->where('status', 1)
            ->with(['color', 'size'])
            ->get();
            
        return StockResource::collection($stocks);
    }
} 