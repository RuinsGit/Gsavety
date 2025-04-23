<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartApiController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        try {
            DB::connection()->getPdo();
            Log::info('Veritabanı bağlantısı başarılı');
        } catch (\Exception $e) {
            Log::error('Veritabanı bağlantı hatası: ' . $e->getMessage());
        }
    }
    
    /**
     * Sepet içeriğini görüntüle
     */
    public function index(Request $request)
    {
        try {
            // Token üzerinden kullanıcıyı belirle
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            Log::info('Sepet görüntüleniyor', ['user_id' => $userId, 'identifier' => $sessionId]);
            
            // Sepeti bul veya oluştur
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            Log::info('Sepet bulundu', ['cart_id' => $cart->id, 'items_count' => $cart->items->count()]);
            
            $formattedCart = $this->formatCartResponse($cart);
            
            return response()->json([
                'success' => true,
                'data' => $formattedCart
            ]);
        } catch (\Exception $e) {
            Log::error('Sepet görüntüleme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sepet görüntülenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sepete ürün ekle
     */
    public function addToCart(Request $request)
    {
        try {
            DB::beginTransaction();
            
            Log::info('Sepete ürün ekleme isteği:', $request->all());
            
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'color_id' => 'nullable|exists:product_colors,id',
                'size_id' => 'nullable|exists:product_sizes,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $product = Product::findOrFail($request->product_id);
            
            if (!$product->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu ürün satışa uygun değil'
                ], 400);
            }
            
            // Stok kontrolü
            $stock = null;
            
            if ($request->has('color_id') && $request->has('size_id')) {
                $stock = ProductStock::where('product_id', $product->id)
                    ->where('product_color_id', $request->color_id)
                    ->where('product_size_id', $request->size_id)
                    ->where('status', 1)
                    ->first();
            } elseif ($request->has('color_id')) {
                $stock = ProductStock::where('product_id', $product->id)
                    ->where('product_color_id', $request->color_id)
                    ->where('status', 1)
                    ->first();
            } elseif ($request->has('size_id')) {
                $stock = ProductStock::where('product_id', $product->id)
                    ->where('product_size_id', $request->size_id)
                    ->where('status', 1)
                    ->first();
            } else {
                $stock = ProductStock::where('product_id', $product->id)
                    ->whereNull('product_color_id')
                    ->whereNull('product_size_id')
                    ->where('status', 1)
                    ->first();
            }
            
            if (!$stock || $stock->quantity < $request->quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Yeterli stok bulunmuyor'
                ], 400);
            }
            
            // Sepet işlemleri
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            Log::info('Sepete ürün ekleniyor', [
                'user_id' => $userId, 
                'identifier' => $sessionId,
                'product_id' => $product->id
            ]);
            
            // Sepeti bul veya oluştur
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            Log::info('Sepet oluşturuldu/bulundu', ['cart_id' => $cart->id]);
            
            $price = $stock->price ?? ($product->discount_price ?: $product->price);
            
            // Ürün sepette var mı kontrol et
            $cartItem = $cart->items()
                ->where('product_id', $product->id)
                ->where(function($query) use ($request) {
                    if ($request->has('color_id')) {
                        $query->where('product_color_id', $request->color_id);
                    } else {
                        $query->whereNull('product_color_id');
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->has('size_id')) {
                        $query->where('product_size_id', $request->size_id);
                    } else {
                        $query->whereNull('product_size_id');
                    }
                })
                ->first();
            
            if ($cartItem) {
                // Ürün sepette varsa miktarını güncelle
                $cartItem->quantity += $request->quantity;
                $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
                $cartItem->save();
                
                Log::info('Sepetteki ürün güncellendi', [
                    'cart_item_id' => $cartItem->id, 
                    'quantity' => $cartItem->quantity
                ]);
            } else {
                // Ürün sepette yoksa yeni ekle
                $cartItem = new CartItem([
                    'product_id' => $product->id,
                    'product_color_id' => $request->color_id,
                    'product_size_id' => $request->size_id,
                    'price' => $price,
                    'quantity' => $request->quantity,
                    'subtotal' => $price * $request->quantity
                ]);
                
                $cart->items()->save($cartItem);
                
                Log::info('Sepete yeni ürün eklendi', [
                    'cart_item_id' => $cartItem->id, 
                    'product_id' => $product->id
                ]);
            }
            
            // Sepet toplamını güncelle
            $this->updateCartTotals($cart);
            
            // Renk ve boyut bilgilerini al
            $color = null;
            $size = null;
            
            if ($request->color_id) {
                $color = ProductColor::find($request->color_id);
            }
            
            if ($request->size_id) {
                $size = ProductSize::find($request->size_id);
            }
            
            // Ürün alt toplamını hesapla
            $subtotal = $price * $cartItem->quantity;
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün sepete eklendi',
                'data' => [
                    'cart_id' => $cartItem->id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $price,
                        'image' => $product->main_image ? asset($product->main_image) : null
                    ],
                    'color' => $color ? [
                        'id' => $color->id,
                        'name' => $color->{"color_name_" . app()->getLocale()},
                        'color_code' => $color->color_code,
                        'color_image' => $color->color_image ? asset($color->color_image) : null
                    ] : null,
                    'size' => $size ? [
                        'id' => $size->id,
                        'name' => $size->{"size_name_" . app()->getLocale()},
                        'value' => $size->size_value
                    ] : null,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $subtotal,
                    'cart_summary' => [
                        'total_amount' => $cart->total_amount,
                        'item_count' => $cart->items()->count(),
                        'total_items' => $cart->items()->sum('quantity')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sepete ürün eklerken hata: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sepete ürün eklenirken bir hata oluştu: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Sepetten ürün çıkar
     */
    public function removeFromCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cart_item_id' => 'required|exists:cart_items,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            // Sepeti bul
            $cart = $this->getCart($userId, $sessionId);
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sepet bulunamadı'
                ], 404);
            }
            
            // Sepet öğesini bul
            $cartItem = $cart->items()->find($request->cart_item_id);
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belirtilen ürün sepette bulunamadı'
                ], 404);
            }
            
            // Silinmeden önce öğe bilgilerini kaydedelim
            $removedItemDetails = [
                'cart_item_id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity
            ];
            
            // Sepet öğesini sil
            $cartItem->delete();
            
            // Sepet toplamını güncelle
            $this->updateCartTotals($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün sepetten kaldırıldı',
                'data' => [
                    'removed_item' => $removedItemDetails,
                    'cart_summary' => [
                        'total_amount' => $cart->total_amount,
                        'item_count' => $cart->items()->count(),
                        'total_items' => $cart->items()->sum('quantity')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Sepetten ürün kaldırma hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Ürün sepetten kaldırılırken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sepetteki ürün miktarını güncelle
     */
    public function updateCartItem(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'cart_item_id' => 'required|exists:cart_items,id',
                'quantity' => 'required|integer|min:1',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            // Sepeti bul
            $cart = $this->getCart($userId, $sessionId);
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sepet bulunamadı'
                ], 404);
            }
            
            // Sepet öğesini bul
            $cartItem = $cart->items()->find($request->cart_item_id);
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belirtilen ürün sepette bulunamadı'
                ], 404);
            }
            
            // Stok kontrolü
            $stock = null;
            
            if ($cartItem->product_color_id && $cartItem->product_size_id) {
                $stock = ProductStock::where('product_id', $cartItem->product_id)
                    ->where('product_color_id', $cartItem->product_color_id)
                    ->where('product_size_id', $cartItem->product_size_id)
                    ->where('status', 1)
                    ->first();
            } elseif ($cartItem->product_color_id) {
                $stock = ProductStock::where('product_id', $cartItem->product_id)
                    ->where('product_color_id', $cartItem->product_color_id)
                    ->whereNull('product_size_id')
                    ->where('status', 1)
                    ->first();
            } elseif ($cartItem->product_size_id) {
                $stock = ProductStock::where('product_id', $cartItem->product_id)
                    ->whereNull('product_color_id')
                    ->where('product_size_id', $cartItem->product_size_id)
                    ->where('status', 1)
                    ->first();
            } else {
                $stock = ProductStock::where('product_id', $cartItem->product_id)
                    ->whereNull('product_color_id')
                    ->whereNull('product_size_id')
                    ->where('status', 1)
                    ->first();
            }
            
            if (!$stock || $stock->quantity < $request->quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Yeterli stok bulunmuyor'
                ], 400);
            }
            
            // Sepet öğesini güncelle
            $cartItem->quantity = $request->quantity;
            $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
            $cartItem->save();
            
            // Sepet toplamını güncelle
            $this->updateCartTotals($cart);
            
            // Renk ve boyut bilgilerini al
            $product = $cartItem->product;
            $color = $cartItem->color;
            $size = $cartItem->size;
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün miktarı güncellendi',
                'data' => [
                    'cart_item_id' => $cartItem->id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->{app()->getLocale() ? 'name_' . app()->getLocale() : 'name_az'},
                        'price' => $cartItem->price,
                        'image' => $product->main_image ? asset($product->main_image) : null
                    ],
                    'color' => $color ? [
                        'id' => $color->id,
                        'name' => $color->{app()->getLocale() ? 'color_name_' . app()->getLocale() : 'color_name_az'},
                        'color_code' => $color->color_code,
                        'color_image' => $color->color_image ? asset($color->color_image) : null
                    ] : null,
                    'size' => $size ? [
                        'id' => $size->id,
                        'name' => $size->{app()->getLocale() ? 'size_name_' . app()->getLocale() : 'size_name_az'},
                        'value' => $size->size_value
                    ] : null,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->subtotal,
                    'cart_summary' => [
                        'total_amount' => $cart->total_amount,
                        'item_count' => $cart->items()->count(),
                        'total_items' => $cart->items()->sum('quantity')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sepet öğesi güncelleme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Ürün miktarı güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sepeti boşalt
     */
    public function clearCart(Request $request)
    {
        try {
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            // Sepeti bul
            $cart = $this->getCart($userId, $sessionId);
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sepet bulunamadı'
                ], 404);
            }
            
            // Sepet öğelerini temizlemeden önce bilgileri al
            $itemCount = $cart->items()->count();
            
            // Sepet öğelerini sil
            $cart->items()->delete();
            
            // Sepet toplamını sıfırla
            $cart->total_amount = 0;
            $cart->item_count = 0;
            $cart->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Sepet boşaltıldı',
                'data' => [
                    'cleared_items_count' => $itemCount,
                    'cart_summary' => [
                        'total_amount' => 0,
                        'item_count' => 0,
                        'total_items' => 0
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Sepet temizleme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sepet boşaltılırken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kimlik doğrulaması yapılmış kullanıcının sepetini getirir
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCart(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı'
                ], 401);
            }
            
            $cart = $this->getOrCreateCart($user->id, 'user_' . $user->id);
            
            $formattedCart = $this->formatCartResponse($cart);
            
            // Kullanıcı detaylarını ekle
            $formattedCart['user'] = [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone ?? null
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı sepeti',
                'data' => $formattedCart
            ]);
        } catch (\Exception $e) {
            Log::error('Kullanıcı sepeti getirme hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı sepeti getirilirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Token veya benzersiz bir tanımlayıcı alarak sepet tanımlayıcısını döndürür
     * 
     * @param Request $request
     * @return string
     */
    private function getCartIdentifier(Request $request)
    {
        try {
            // Giriş yapmış kullanıcılar için
            if (Auth::check()) {
                $user = Auth::user();
                return 'user_' . $user->id;
            }
            
            // Bearer token içeren istekler için
            $bearerToken = $request->bearerToken();
            if ($bearerToken) {
                return 'token_' . $bearerToken;
            }
            
            // API token ile gelen istekler için
            $apiToken = $request->header('X-API-Token');
            if ($apiToken) {
                return 'api_' . $apiToken;
            }
            
            // Token olmayan istekler için device ID veya benzersiz tanımlayıcı
            $deviceId = $request->header('X-Device-ID');
            if ($deviceId) {
                return 'device_' . $deviceId;
            }
            
            // Cookie kontrolü (fallback)
            if ($request->cookie('cart_session_id')) {
                return $request->cookie('cart_session_id');
            }
            
            // Session ID (fallback)
            if (method_exists($request, 'session') && $request->session()->getId()) {
                return 'session_' . $request->session()->getId();
            }
            
            // Hiçbiri yoksa random ID oluştur
            $randomId = 'temp_' . Str::random(30);
            return $randomId;
        } catch (\Exception $e) {
            Log::error('Cart identifier alma hatası: ' . $e->getMessage());
            return 'fallback_' . Str::random(20);
        }
    }
    
    /**
     * Sepeti bul veya oluştur (token bazlı)
     * 
     * @param int|null $userId
     * @param string $identifier
     * @return Cart
     */
    private function getOrCreateCart($userId, $identifier)
    {
        try {
            Log::info('getOrCreateCart çağrıldı', ['user_id' => $userId, 'identifier' => $identifier]);
            
            $cart = null;
            
            if ($userId) {
                // Kullanıcı giriş yapmışsa, kullanıcıya ait sepeti bul
                $cart = Cart::where('user_id', $userId)
                    ->where('is_active', true)
                    ->first();
                
                if ($cart) {
                    Log::info('Kullanıcıya ait aktif sepet bulundu', ['cart_id' => $cart->id]);
                    return $cart;
                }
                
                // Kullanıcıya ait sepet yoksa, token/session ID'ye ait sepeti kontrol et
                $identifierCart = Cart::where('session_id', $identifier)
                    ->whereNull('user_id')
                    ->where('is_active', true)
                    ->first();
                
                if ($identifierCart) {
                    // Tanımlayıcıya ait sepet varsa, kullanıcıya bağla
                    $identifierCart->user_id = $userId;
                    $identifierCart->save();
                    Log::info('Token/session sepeti kullanıcıya bağlandı', ['cart_id' => $identifierCart->id, 'user_id' => $userId]);
                    return $identifierCart;
                }
                
                // Hiçbir sepet yoksa yeni oluştur
                $cart = new Cart();
                $cart->user_id = $userId;
                $cart->session_id = $identifier;
                $cart->is_active = true;
                $cart->save();
                
                Log::info('Kullanıcı için yeni sepet oluşturuldu', ['cart_id' => $cart->id, 'user_id' => $userId]);
                
                return $cart;
            } else {
                // Kullanıcı giriş yapmamışsa, token/session ID'ye göre sepeti bul
                $cart = Cart::where('session_id', $identifier)
                    ->whereNull('user_id')
                    ->where('is_active', true)
                    ->first();
                
                if ($cart) {
                    Log::info('Token/identifier için mevcut sepet bulundu', ['cart_id' => $cart->id, 'identifier' => $identifier]);
                    return $cart;
                }
                
                // Sepet yoksa yeni oluştur
                $cart = new Cart();
                $cart->session_id = $identifier;
                $cart->is_active = true;
                $cart->save();
                
                Log::info('Token/identifier için yeni sepet oluşturuldu', ['cart_id' => $cart->id, 'identifier' => $identifier]);
                
                return $cart;
            }
        } catch (\Exception $e) {
            Log::error('getOrCreateCart hatası: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sepeti bul
     * 
     * @param int|null $userId
     * @param string $identifier
     * @return Cart|null
     */
    private function getCart($userId, $identifier)
    {
        try {
            if ($userId) {
                // Önce kullanıcı ID'ye göre ara
                $cart = Cart::where('user_id', $userId)
                    ->where('is_active', true)
                    ->first();
                    
                if ($cart) {
                    return $cart;
                }
            }
            
            // Tanımlayıcıya göre ara
            return Cart::where('session_id', $identifier)
                ->where(function($query) use ($userId) {
                    if ($userId) {
                        $query->where('user_id', $userId)->orWhereNull('user_id');
                    } else {
                        $query->whereNull('user_id');
                    }
                })
                ->where('is_active', true)
                ->first();
        } catch (\Exception $e) {
            Log::error('getCart hatası: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sepet toplamını güncelle
     * 
     * @param Cart $cart
     * @return void
     */
    private function updateCartTotals(Cart $cart)
    {
        try {
            $totalAmount = $cart->items()->sum('subtotal');
            $itemCount = $cart->items()->count();
            
            $cart->total_amount = $totalAmount;
            $cart->item_count = $itemCount;
            $cart->save();
            
            Log::info('Sepet toplamı güncellendi', [
                'cart_id' => $cart->id, 
                'total' => $totalAmount, 
                'items' => $itemCount
            ]);
        } catch (\Exception $e) {
            Log::error('updateCartTotals hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Birden fazla ürünü sepete ekle
     */
    public function addMultipleToCart(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.color_id' => 'nullable|exists:product_colors,id',
                'products.*.size_id' => 'nullable|exists:product_sizes,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            Log::info('Sepete çoklu ürün ekleme başladı', [
                'user_id' => $userId,
                'identifier' => $sessionId,
                'products_count' => count($request->products)
            ]);
            
            // Sepeti bul veya oluştur
            $cart = $this->getOrCreateCart($userId, $sessionId);
            
            $addedItems = [];
            $errors = [];
            
            foreach ($request->products as $productData) {
                $product = Product::find($productData['product_id']);
                
                if (!$product) {
                    $errors[] = [
                        'product_id' => $productData['product_id'],
                        'message' => 'Ürün bulunamadı'
                    ];
                    continue;
                }
                
                if (!$product->status) {
                    $errors[] = [
                        'product_id' => $productData['product_id'],
                        'message' => 'Bu ürün satışa uygun değil'
                    ];
                    continue;
                }
                
                // Stok kontrolü
                $stock = null;
                $colorId = $productData['color_id'] ?? null;
                $sizeId = $productData['size_id'] ?? null;
                
                if ($colorId && $sizeId) {
                    $stock = ProductStock::where('product_id', $product->id)
                        ->where('product_color_id', $colorId)
                        ->where('product_size_id', $sizeId)
                        ->where('status', 1)
                        ->first();
                } elseif ($colorId) {
                    $stock = ProductStock::where('product_id', $product->id)
                        ->where('product_color_id', $colorId)
                        ->whereNull('product_size_id')
                        ->where('status', 1)
                        ->first();
                } elseif ($sizeId) {
                    $stock = ProductStock::where('product_id', $product->id)
                        ->whereNull('product_color_id')
                        ->where('product_size_id', $sizeId)
                        ->where('status', 1)
                        ->first();
                } else {
                    $stock = ProductStock::where('product_id', $product->id)
                        ->whereNull('product_color_id')
                        ->whereNull('product_size_id')
                        ->where('status', 1)
                        ->first();
                }
                
                if (!$stock || $stock->quantity < $productData['quantity']) {
                    $errors[] = [
                        'product_id' => $productData['product_id'],
                        'color_id' => $colorId,
                        'size_id' => $sizeId,
                        'message' => 'Yeterli stok bulunmuyor'
                    ];
                    continue;
                }
                
                // Fiyat hesaplama
                $price = $stock->price ?? ($product->discount_price ?: $product->price);
                
                // Ürün sepette var mı kontrol et
                $cartItem = $cart->items()
                    ->where('product_id', $product->id)
                    ->where(function($query) use ($colorId) {
                        if ($colorId) {
                            $query->where('product_color_id', $colorId);
                        } else {
                            $query->whereNull('product_color_id');
                        }
                    })
                    ->where(function($query) use ($sizeId) {
                        if ($sizeId) {
                            $query->where('product_size_id', $sizeId);
                        } else {
                            $query->whereNull('product_size_id');
                        }
                    })
                    ->first();
                
                if ($cartItem) {
                    // Ürün sepette varsa miktarını güncelle
                    $cartItem->quantity += $productData['quantity'];
                    $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
                    $cartItem->save();
                    
                    Log::info('Çoklu eklemede ürün güncellendi', [
                        'cart_item_id' => $cartItem->id,
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity
                    ]);
                } else {
                    // Ürün sepette yoksa yeni ekle
                    $cartItem = new CartItem([
                        'product_id' => $product->id,
                        'product_color_id' => $colorId,
                        'product_size_id' => $sizeId,
                        'price' => $price,
                        'quantity' => $productData['quantity'],
                        'subtotal' => $price * $productData['quantity']
                    ]);
                    
                    $cart->items()->save($cartItem);
                    
                    Log::info('Çoklu eklemede yeni ürün eklendi', [
                        'cart_item_id' => $cartItem->id,
                        'product_id' => $product->id
                    ]);
                }
                
                // Renk ve boyut bilgilerini al
                $color = null;
                $size = null;
                
                if ($colorId) {
                    $color = ProductColor::find($colorId);
                }
                
                if ($sizeId) {
                    $size = ProductSize::find($sizeId);
                }
                
                // Eklenen ürün bilgilerini hazırla
                $addedItems[] = [
                    'cart_item_id' => $cartItem->id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->{app()->getLocale() ? 'name_' . app()->getLocale() : 'name_az'},
                        'slug' => $product->slug,
                        'price' => $price,
                        'original_price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'discount_rate' => $product->discount_price ? round((($product->price - $product->discount_price) / $product->price) * 100) : 0,
                        'image' => $product->main_image ? asset($product->main_image) : null
                    ],
                    'color' => $color ? [
                        'id' => $color->id,
                        'name' => $color->{app()->getLocale() ? 'color_name_' . app()->getLocale() : 'color_name_az'},
                        'color_code' => $color->color_code,
                        'color_image' => $color->color_image ? asset($color->color_image) : null
                    ] : null,
                    'size' => $size ? [
                        'id' => $size->id,
                        'name' => $size->{app()->getLocale() ? 'size_name_' . app()->getLocale() : 'size_name_az'},
                        'value' => $size->size_value
                    ] : null,
                    'quantity' => $cartItem->quantity,
                    'requested_quantity' => $productData['quantity'],
                    'unit_price' => $price,
                    'subtotal' => $cartItem->subtotal
                ];
            }
            
            // Sepet toplamını güncelle
            $this->updateCartTotals($cart);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($addedItems) > 0 ? 'Ürünler sepete eklendi' : 'Hiçbir ürün sepete eklenemedi',
                'data' => [
                    'added_items' => $addedItems,
                    'errors' => $errors,
                    'cart_summary' => [
                        'total_amount' => $cart->total_amount,
                        'item_count' => $cart->items()->count(),
                        'total_items' => $cart->items()->sum('quantity')
                    ]
                ]
            ], count($addedItems) > 0 ? 200 : 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sepete çoklu ürün eklerken hata: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sepete ürünler eklenirken bir hata oluştu: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Sepet yanıtını standart bir formatta biçimlendirir
     * 
     * @param Cart $cart
     * @return array
     */
    private function formatCartResponse($cart)
    {
        $items = [];
        $totalItems = 0;
        $totalAmount = 0;
        
        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product) continue;
            
            $color = $item->color;
            $size = $item->size;
            
            $price = $item->price;
            $quantity = $item->quantity;
            $subtotal = $item->subtotal;
            $totalItems += $quantity;
            $totalAmount += $subtotal;
            
            // Ürün adını dile göre al
            $locale = app()->getLocale();
            $nameField = 'name_' . ($locale ?: 'az');
            $productName = $product->$nameField ?? $product->name_az ?? $product->name ?? 'Ürün Adı';
            
            // Ürün bilgilerini hazırla
            $productInfo = [
                'id' => $product->id,
                'name' => $productName,
                'slug' => $product->slug ?? null,
                'original_price' => (float) $product->price,
                'discount_price' => $product->discount_price ? (float) $product->discount_price : null,
                'discount_rate' => $product->discount_price ? (int) round((($product->price - $product->discount_price) / $product->price) * 100) : 0,
                'main_image' => $product->main_image ? asset($product->main_image) : null,
                'sku' => $product->sku ?? null
            ];
            
            // Renk bilgilerini hazırla
            $colorInfo = null;
            if ($color) {
                $colorNameField = 'color_name_' . ($locale ?: 'az');
                $colorInfo = [
                    'id' => $color->id,
                    'name' => $color->$colorNameField ?? $color->color_name_az ?? $color->color_name ?? 'Renk',
                    'code' => $color->color_code,
                    'image' => $color->color_image ? asset($color->color_image) : null
                ];
            }
            
            // Beden bilgilerini hazırla
            $sizeInfo = null;
            if ($size) {
                $sizeNameField = 'size_name_' . ($locale ?: 'az');
                $sizeInfo = [
                    'id' => $size->id,
                    'name' => $size->$sizeNameField ?? $size->size_name_az ?? $size->size_name ?? 'Beden',
                    'value' => $size->size_value
                ];
            }
            
            // Sepet öğesi
            $items[] = [
                'id' => $item->id,
                'product' => $productInfo,
                'color' => $colorInfo,
                'size' => $sizeInfo,
                'price' => (float) $price,
                'quantity' => (int) $quantity,
                'subtotal' => (float) $subtotal,
                'added_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s')
            ];
        }
        
        // Sepet özeti bilgilerini hazırla
        $summary = [
            'total_amount' => (float) $totalAmount,
            'item_count' => count($items),
            'total_items' => (int) $totalItems,
            'currency' => 'TL',
            'created_at' => $cart->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $cart->updated_at->format('Y-m-d H:i:s')
        ];
        
        return [
            'cart_id' => $cart->id,
            'items' => $items,
            'summary' => $summary
        ];
    }

    /**
     * Belirli ürün, renk ve boyut kombinasyonunu sepetten temizler
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCartByProductAttributes(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'color_id' => 'nullable|exists:product_colors,id',
                'size_id' => 'nullable|exists:product_sizes,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $userId = Auth::id();
            $sessionId = $this->getCartIdentifier($request);
            
            // Sepeti bul
            $cart = $this->getCart($userId, $sessionId);
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sepet bulunamadı'
                ], 404);
            }
            
            // Belirtilen ürün, renk ve boyut özelliklerine göre sepet öğelerini bul
            $query = $cart->items()->where('product_id', $request->product_id);
            
            // Renk kimliği varsa filtreye ekle
            if ($request->has('color_id')) {
                $query->where('product_color_id', $request->color_id);
            }
            
            // Boyut kimliği varsa filtreye ekle
            if ($request->has('size_id')) {
                $query->where('product_size_id', $request->size_id);
            }
            
            // Silinecek öğeleri bul
            $itemsToRemove = $query->get();
            
            if ($itemsToRemove->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belirtilen özelliklere sahip ürün sepette bulunamadı'
                ], 404);
            }
            
            // Silinmeden önce öğe bilgilerini kaydet
            $removedItems = [];
            foreach ($itemsToRemove as $item) {
                $removedItems[] = [
                    'cart_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_color_id' => $item->product_color_id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal
                ];
            }
            
            // Öğeleri sil
            $totalRemoved = $query->delete();
            
            // Sepet toplamını güncelle
            $this->updateCartTotals($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün(ler) sepetten kaldırıldı',
                'data' => [
                    'removed_items' => $removedItems,
                    'total_removed' => $totalRemoved,
                    'cart_summary' => [
                        'total_amount' => $cart->total_amount,
                        'item_count' => $cart->items()->count(),
                        'total_items' => $cart->items()->sum('quantity')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Sepetten ürün kaldırma hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Ürün sepetten kaldırılırken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
} 