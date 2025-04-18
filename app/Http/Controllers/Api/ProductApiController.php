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
        // Ana sorgu
        $query = Product::query();
        
        // Kategori filtresi
        if ($request->has('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        
        // Fiyat aralığı filtresi
        if ($request->has('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }
        
        // Ürün özelliklerine göre filtreleme
        if ($request->has('properties')) {
            $properties = is_array($request->properties) ? $request->properties : json_decode($request->properties, true);
            
            foreach ($properties as $propertyId => $values) {
                $query->whereHas('properties', function($q) use ($propertyId, $values) {
                    $q->where('properties.id', $propertyId)
                      ->whereIn('product_property.value', (array)$values);
                });
            }
        }
        
        // İlişkili ürün bilgilerini yükle
        $query->with([
            'categories',
            'properties',
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
        
        // Status filtresi (varsayılan olarak aktif ürünleri göster)
        $status = $request->has('status') ? (int)$request->status : 1;
        $query->where('status', $status);
        
        // Öne çıkan ürünler filtresi
        if ($request->has('featured')) {
            $query->where('is_featured', (bool)$request->featured);
        }
        
        // Sıralama
        if ($request->has('sort_by')) {
            $sortDirection = $request->has('sort_dir') ? $request->sort_dir : 'asc';
            
            switch ($request->sort_by) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
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
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        
        // Sayfalama
        $perPage = $request->has('per_page') ? (int)$request->per_page : 15;
        
        return ProductResource::collection($query->paginate($perPage));
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
            ->with([
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
            ])
            ->orderBy('id', 'desc')
            ->take(10)
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
    
    /**
     * Bir ürünün tüm detaylarını getir (renkler, boyutlar, stoklar, resimler)
     *
     * @param  string  $productId
     * @return \Illuminate\Http\Response
     */
    public function getProductDetails($productId)
    {
        $product = Product::with([
            'categories',
            'properties',
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
        ])->findOrFail($productId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'product' => new ProductResource($product),
                'colors' => ColorResource::collection($product->colors),
                'sizes' => SizeResource::collection($product->sizes),
                'stocks' => StockResource::collection($product->stocks),
                'images' => $product->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset($image->image)
                    ];
                })
            ]
        ]);
    }
} 