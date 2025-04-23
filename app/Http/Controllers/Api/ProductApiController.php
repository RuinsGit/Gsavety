<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ColorResource;
use App\Http\Resources\SizeResource;
use App\Http\Resources\StockResource;
use App\Http\Resources\PropertyTypeResource;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use App\Models\ProductProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Debug için SQL sorgusunu logla
        \DB::enableQueryLog();
        
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
            $locale = $request->input('locale', app()->getLocale());
            
            foreach ($properties as $propertyId => $value) {
                if (empty($value)) continue;
                
                // Basit filtreleme mantığı - doğrudan property değerine göre
                $query->whereHas('properties', function($q) use ($value) {
                    $q->where('property_value_az', 'like', "%{$value}%")
                      ->orWhere('property_value_en', 'like', "%{$value}%")
                      ->orWhere('property_value_ru', 'like', "%{$value}%");
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
        
        // Sayfalama - limitini yüksek bir değere ayarlıyoruz (20 istek için yeterli olacak)
        $perPage = $request->has('per_page') ? (int)$request->per_page : 100;
        
        // Debug için SQL sorgusunu logla
        \DB::enableQueryLog();
        $result = $query->paginate($perPage);
        $queries = \DB::getQueryLog();
        \Log::info('Ürün sorgulama SQL:', ['queries' => $queries]);
        
        return ProductResource::collection($result);
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
            ->paginate(100);
            
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
    
    /**
     * Ürün özelliklerine göre filtreleme seçeneklerini döndür
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFilterOptions(Request $request)
    {
        // Kategori ID'sine göre filtreleme
        $categoryId = $request->input('category_id');
        
        // Veritabanından ürün özellikleri sorgula
        $query = ProductProperty::query()
            ->select('id', 'property_type', 'property_name_az', 'property_name_en', 'property_name_ru', 
                    'property_value_az', 'property_value_en', 'property_value_ru')
            ->orderBy('property_type')
            ->orderBy('property_name_az');
            
        // Eğer kategori ID'si varsa, o kategoriye ait ürünlerin özelliklerini getir
        if ($categoryId) {
            $query->whereHas('product', function($q) use ($categoryId) {
                $q->whereHas('categories', function($q2) use ($categoryId) {
                    $q2->where('categories.id', $categoryId);
                });
            });
        }
        
        $properties = $query->get();
        
        // Özellik tipine göre grupla
        $groupedProperties = $properties->groupBy('property_type');
        
        // Görünür isim dönüşümü için
        $locale = $request->input('locale', 'az');
        
        // Filtre dizisini oluştur
        $filters = [];
        $filterIndex = 1;
        
        foreach ($groupedProperties as $propertyType => $typeProperties) {
            $typeName = $this->getPropertyTypeName($propertyType);
            
            // Bu tip için en küçük ID'ye sahip olan property name'i bul
            $minIdProperty = $typeProperties->sortBy('id')->first();
            $propertyName = $minIdProperty ? $minIdProperty->{'property_name_' . $locale} : '';
            
            // Özellik grubunu oluştur
            $propertyGroup = [
                'id' => $filterIndex++,
                'name' => $propertyName,
                'filter_type' => $typeName,
                'properties' => []
            ];
            
            // Benzersiz özellik değerlerini al
            $uniqueValues = $typeProperties->unique(function ($item) use ($locale) {
                return $item->{'property_value_' . $locale};
            });
            
            // Her benzersiz özellik değeri için
            foreach ($uniqueValues as $index => $property) {
                if (!empty($property->{'property_value_' . $locale})) {
                    $propertyGroup['properties'][] = [
                        'id' => $property->id,
                        'value' => $property->{'property_value_' . $locale}
                    ];
                }
            }
            
            // Eğer grupta özellik varsa, filtrelere ekle
            if (count($propertyGroup['properties']) > 0) {
                $filters[] = $propertyGroup;
            }
        }
        
        // Fiyat aralığı bilgisi için ürünlerin min ve max fiyatlarını bul
        $priceQuery = Product::query()->where('status', 1);
        
        // Kategori filtresi
        if ($categoryId) {
            $priceQuery->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        
        $priceRange = [
            'min' => $priceQuery->min('price') ?: 0,
            'max' => $priceQuery->max('price') ?: 1000
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'filters' => $filters,
                'price_range' => $priceRange
            ]
        ]);
    }
    
    /**
     * Property tipi için görünen isim döndürür
     * 
     * @param string $type
     * @return string
     */
    private function getPropertyTypeName($type)
    {
        $typeNames = [
            'technical' => 'Texniki Özəlliklər',
            'physical' => 'Fiziki Özəlliklər',
            'material' => 'Material',
            'usage' => 'İstifadə Qaydaları',
            'other' => 'Digər'
        ];
        
        return $typeNames[$type] ?? 'Digər';
    }
    
    /**
     * Property ID'sine göre property_type ve property_name bilgilerini döndürür
     * 
     * @param int $id
     * @return array|null
     */
    private function getPropertyInfoById($id)
    {
        // ID bazlı property bilgilerini saklamak için statik bir cache mekanizması
        static $propertyInfoCache = [];
        
        // Eğer cache'de bu ID için bilgi varsa, direkt döndür
        if (isset($propertyInfoCache[$id])) {
            return $propertyInfoCache[$id];
        }
        
        // ID aralıklarına göre property_type belirle
        $propertyTypes = [
            // 1-100 arası ID'ler için Texniki Özəlliklər
            [1, 100, 'technical'],
            // 101-200 arası ID'ler için Fiziki Özəlliklər
            [101, 200, 'physical'],
            // 201-300 arası ID'ler için Material
            [201, 300, 'material'],
            // 301-400 arası ID'ler için İstifadə Qaydaları
            [301, 400, 'usage'],
            // 401-500 arası ID'ler için Digər
            [401, 500, 'other'],
            // 1000-1999 arası ID'ler Texniki ID bazlı RAM, CPU
            [1001, 1100, 'technical', 'RAM'],
            [1101, 1200, 'technical', 'CPU'],
            // 2000-2999 arası ID'ler için Rəng
            [5001, 5999, 'physical', 'Rəng']
        ];
        
        // ID'ye göre property_type ve property_name eşleştirmesi yap
        foreach ($propertyTypes as $range) {
            if ($id >= $range[0] && $id <= $range[1]) {
                $result = [
                    'property_type' => $range[2],
                    'property_name' => $range[3] ?? null
                ];
                
                // Sonucu cache'e ekle
                $propertyInfoCache[$id] = $result;
                
                return $result;
            }
        }
        
        // ID için bir eşleşme bulunamadı
        $propertyInfoCache[$id] = null;
        return null;
    }
    
    /**
     * Ürünleri yeni/eski olarak sıralama
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function sortByAge(Request $request)
    {
        // Ana sorgu
        $query = Product::query();
        
        // Status filtresi (varsayılan olarak aktif ürünleri göster)
        $status = $request->has('status') ? (int)$request->status : 1;
        $query->where('status', $status);
        
        // Kategori filtresi
        if ($request->has('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        
        // Sıralama türü ("new" veya "old")
        $sortType = $request->input('sort_type', 'new');
        
        if ($sortType === 'new') {
            // Yeni ürünleri getir (oluşturulma tarihine göre azalan sırada)
            $query->orderBy('created_at', 'desc');
        } else {
            // Eski ürünleri getir (oluşturulma tarihine göre artan sırada)
            $query->orderBy('created_at', 'asc');
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
        
        // Sayfalama - limitini yüksek bir değere ayarlıyoruz
        $perPage = $request->has('per_page') ? (int)$request->per_page : 100;
        
        return ProductResource::collection($query->paginate($perPage));
    }
} 