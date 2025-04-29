<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductStock;

class OrderApiController extends Controller
{
    /**
     * Tüm siparişleri listele
     */
    public function index(Request $request)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için geçerli bir token gerekiyor.'
            ], 401);
        }
        
        // Sipariş tipine göre filtreleme
        $type = $request->input('type');
        $query = Order::with(['items']);
        
        if ($type && in_array($type, ['retail', 'corporate'])) {
            $query->where('type', $type);
        }
        
        // Admin kullanıcıları tüm siparişleri görebilir
        if ($user->role === 'admin') {
            $orders = $query->latest()->paginate(20);
        } else {
            // Normal kullanıcılar sadece kendi siparişlerini görebilir
            $orders = $query->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Yeni sipariş oluştur
     */
    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Sipariş oluşturmak için checkout endpoint\'ini kullanın.'
        ], 400);
    }

    /**
     * Belirli bir siparişin detaylarını göster
     */
    public function show(Request $request, $id)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için geçerli bir token gerekiyor.'
            ], 401);
        }
        
        $order = Order::with(['items', 'items.product'])->findOrFail($id);
        
        // Yetki kontrolü - admin tüm siparişleri, kullanıcı kendi siparişlerini görebilir
        if ($user->role !== 'admin' && $user->id != $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Bu siparişi görüntüleme yetkiniz bulunmuyor.'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Belirli bir kullanıcının siparişlerini listele
     */
    public function getUserOrders(Request $request, $userId)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için geçerli bir token gerekiyor.'
            ], 401);
        }
        
        // Yetki kontrolü - admin başka kullanıcının siparişlerini görebilir, kullanıcı sadece kendisininkileri
        if ($user->role !== 'admin' && $user->id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kullanıcının siparişlerini görüntüleme yetkiniz bulunmuyor.'
            ], 403);
        }
        
        // Sipariş tipine göre filtreleme
        $type = $request->input('type');
        $query = Order::with(['items'])->where('user_id', $userId);
        
        if ($type && in_array($type, ['retail', 'corporate'])) {
            $query->where('type', $type);
        }
        
        $orders = $query->latest()->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Sipariş durumunu güncelle
     */
    public function updateStatus(Request $request, $id)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        // Admin yetkisi kontrolü
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz bulunmuyor.'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending,processing,completed,cancelled',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Sipariş durumu başarıyla güncellendi.',
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status
            ]
        ]);
    }

    /**
     * Ödeme durumunu güncelle
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        // Admin yetkisi kontrolü
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz bulunmuyor.'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Ödeme durumu başarıyla güncellendi.',
            'data' => [
                'order_id' => $order->id,
                'payment_status' => $order->payment_status
            ]
        ]);
    }

    /**
     * Sipariş tipini güncelle (Perakende/Kurumsal)
     */
    public function updateType(Request $request, $id)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        // Admin yetkisi kontrolü
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz bulunmuyor.'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:retail,corporate',
            'company_name' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $order = Order::findOrFail($id);
        $order->type = $request->type;
        
        // Eğer kurumsal sipariş ise şirket adını da güncelle
        if ($request->type === 'corporate' && $request->has('company_name')) {
            $order->company_name = $request->company_name;
        }
        
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Sipariş tipi başarıyla güncellendi.',
            'data' => [
                'order_id' => $order->id,
                'type' => $order->type,
                'company_name' => $order->company_name
            ]
        ]);
    }

    /**
     * Perakende siparişleri listele veya oluştur
     */
    public function getRetailOrders(Request $request)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için geçerli bir token gerekiyor.'
            ], 401);
        }
        
        // POST isteği ise yeni perakende sipariş oluştur
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'comment' => 'nullable|string',
                'payment_method' => 'nullable|string|in:cash_on_delivery,credit_card,bank_transfer',
                'cart_items' => 'required|array|min:1',
                'cart_items.*.product_id' => 'required|exists:products,id',
                'cart_items.*.quantity' => 'required|integer|min:1',
                'cart_items.*.price' => 'required|numeric|min:0',
                'cart_items.*.color_id' => 'nullable|exists:product_colors,id',
                'cart_items.*.size_id' => 'nullable|exists:product_sizes,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Sepet ürünlerini kontrol et
            $items = [];
            $totalAmount = 0;
            
            foreach ($request->cart_items as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                
                if (!$product || !$product->status) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sepetteki ürünlerden biri artık mevcut değil veya satışta değil.'
                    ], 400);
                }
                
                // Stok kontrolü
                $stock = $this->getProductStock(
                    $cartItem['product_id'], 
                    $cartItem['color_id'] ?? null, 
                    $cartItem['size_id'] ?? null
                );
                
                if (!$stock || $stock->quantity < $cartItem['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => $product->name . ' ürünü için yeterli stok bulunmuyor.'
                    ], 400);
                }
                
                $color = isset($cartItem['color_id']) ? ProductColor::find($cartItem['color_id']) : null;
                $size = isset($cartItem['size_id']) ? ProductSize::find($cartItem['size_id']) : null;
                
                $price = $cartItem['price'];
                $quantity = $cartItem['quantity'];
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;
                
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->{'name_' . app()->getLocale()},
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $subtotal,
                    'color_id' => $cartItem['color_id'] ?? null,
                    'size_id' => $cartItem['size_id'] ?? null,
                    'color_name' => $color ? $color->{'color_name_' . app()->getLocale()} : null,
                    'size_name' => $size ? $size->{'size_name_' . app()->getLocale()} : null,
                    'stock' => $stock
                ];
            }
            
            // Perakende siparişi oluştur
            $order = new Order();
            $order->order_number = 'RET-' . strtoupper(Str::random(10));
            $order->user_id = $user->id;
            $order->type = 'retail'; // Perakende sipariş tipi
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->postal_code = $request->postal_code;
            $order->country = $request->country ?? 'Azerbaijan';
            $order->comment = $request->comment;
            $order->total_amount = $totalAmount;
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->payment_method = $request->payment_method ?? 'cash_on_delivery';
            $order->save();
            
            // Sipariş ürünlerini ekle ve stok güncelle
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->product_name = $item['product_name'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->total = $item['total'];
                $orderItem->color_id = $item['color_id'];
                $orderItem->size_id = $item['size_id'];
                $orderItem->color_name = $item['color_name'];
                $orderItem->size_name = $item['size_name'];
                $orderItem->save();
                
                // Stok güncelle
                $stock = $item['stock'];
                $stock->quantity -= $item['quantity'];
                $stock->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Perakende sipariş başarıyla oluşturuldu.',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status
                ]
            ]);
        }
        
        // GET isteği ise perakende siparişleri listele
        // Admin kullanıcıları tüm siparişleri görebilir
        if ($user->role === 'admin') {
            $orders = Order::with(['items'])
                ->where('type', 'retail')
                ->latest()
                ->paginate(20);
        } else {
            // Normal kullanıcılar sadece kendi siparişlerini görebilir
            $orders = Order::with(['items'])
                ->where('user_id', $user->id)
                ->where('type', 'retail')
                ->latest()
                ->paginate(10);
        }
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Kurumsal siparişleri listele veya oluştur
     */
    public function getCorporateOrders(Request $request)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için geçerli bir token gerekiyor.'
            ], 401);
        }
        
        // POST isteği ise yeni kurumsal sipariş oluştur
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'comment' => 'nullable|string',
                'payment_method' => 'nullable|string|in:cash_on_delivery,credit_card,bank_transfer',
                'company_name' => 'required|string|max:255', // Kurumsal sipariş için şirket adı zorunlu
                'cart_items' => 'required|array|min:1',
                'cart_items.*.product_id' => 'required|exists:products,id',
                'cart_items.*.quantity' => 'required|integer|min:1',
                'cart_items.*.price' => 'required|numeric|min:0',
                'cart_items.*.color_id' => 'nullable|exists:product_colors,id',
                'cart_items.*.size_id' => 'nullable|exists:product_sizes,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Doğrulama hatası',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Sepet ürünlerini kontrol et
            $items = [];
            $totalAmount = 0;
            
            foreach ($request->cart_items as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                
                if (!$product || !$product->status) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sepetteki ürünlerden biri artık mevcut değil veya satışta değil.'
                    ], 400);
                }
                
                // Stok kontrolü
                $stock = $this->getProductStock(
                    $cartItem['product_id'], 
                    $cartItem['color_id'] ?? null, 
                    $cartItem['size_id'] ?? null
                );
                
                if (!$stock || $stock->quantity < $cartItem['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => $product->name . ' ürünü için yeterli stok bulunmuyor.'
                    ], 400);
                }
                
                $color = isset($cartItem['color_id']) ? ProductColor::find($cartItem['color_id']) : null;
                $size = isset($cartItem['size_id']) ? ProductSize::find($cartItem['size_id']) : null;
                
                $price = $cartItem['price'];
                $quantity = $cartItem['quantity'];
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;
                
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->{'name_' . app()->getLocale()},
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $subtotal,
                    'color_id' => $cartItem['color_id'] ?? null,
                    'size_id' => $cartItem['size_id'] ?? null,
                    'color_name' => $color ? $color->{'color_name_' . app()->getLocale()} : null,
                    'size_name' => $size ? $size->{'size_name_' . app()->getLocale()} : null,
                    'stock' => $stock
                ];
            }
            
            // Kurumsal siparişi oluştur
            $order = new Order();
            $order->order_number = 'CORP-' . strtoupper(Str::random(10));
            $order->user_id = $user->id;
            $order->type = 'corporate'; // Kurumsal sipariş tipi
            $order->company_name = $request->company_name;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->postal_code = $request->postal_code;
            $order->country = $request->country ?? 'Azerbaijan';
            $order->comment = $request->comment;
            $order->total_amount = $totalAmount;
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->payment_method = $request->payment_method ?? 'cash_on_delivery';
            $order->save();
            
            // Sipariş ürünlerini ekle ve stok güncelle
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->product_name = $item['product_name'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->total = $item['total'];
                $orderItem->color_id = $item['color_id'];
                $orderItem->size_id = $item['size_id'];
                $orderItem->color_name = $item['color_name'];
                $orderItem->size_name = $item['size_name'];
                $orderItem->save();
                
                // Stok güncelle
                $stock = $item['stock'];
                $stock->quantity -= $item['quantity'];
                $stock->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Kurumsal sipariş başarıyla oluşturuldu.',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status
                ]
            ]);
        }
        
        // GET isteği ise kurumsal siparişleri listele
        // Admin kullanıcıları tüm siparişleri görebilir
        if ($user->role === 'admin') {
            $orders = Order::with(['items'])
                ->where('type', 'corporate')
                ->latest()
                ->paginate(20);
        } else {
            // Normal kullanıcılar sadece kendi siparişlerini görebilir
            $orders = Order::with(['items'])
                ->where('user_id', $user->id)
                ->where('type', 'corporate')
                ->latest()
                ->paginate(10);
        }
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
    
    /**
     * Stok bilgisini getir
     */
    private function getProductStock($productId, $colorId = null, $sizeId = null)
    {
        $query = ProductStock::where('product_id', $productId);
        
        if ($colorId) {
            $query->where('product_color_id', $colorId);
        }
        
        if ($sizeId) {
            $query->where('product_size_id', $sizeId);
        }
        
        return $query->first();
    }

    /**
     * Giriş yapmış (token ile kimlik doğrulaması yapmış) kullanıcının siparişlerini getirir
     */
    public function getMyOrders(Request $request)
    {
        // Token ile gelen kullanıcı bilgisini al
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için geçerli bir token gerekiyor.'
            ], 401);
        }
        
        // Sipariş tipine göre filtreleme
        $type = $request->input('type');
        $query = Order::with(['items', 'items.product', 'items.product.images'])
            ->where('user_id', $user->id);
        
        if ($type && in_array($type, ['retail', 'corporate'])) {
            $query->where('type', $type);
        }
        
        $orders = $query->latest()->paginate(10);
        
        // Sipariş öğelerine ürün görsellerini ekleyelim
        $orders->getCollection()->transform(function ($order) {
            $order->items->transform(function ($item) {
                $product = $item->product;
                if ($product) {
                    // Ana görsel ve diğer görselleri ekleyelim
                    $item->product_image = $product->main_image ? asset($product->main_image) : null;
                   
                }
                return $item;
            });
            return $order;
        });
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
} 