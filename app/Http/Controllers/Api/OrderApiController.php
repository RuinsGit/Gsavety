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
        
        // Admin kullanıcıları tüm siparişleri görebilir
        if ($user->role === 'admin') {
            $orders = Order::with(['items'])->latest()->paginate(20);
        } else {
            // Normal kullanıcılar sadece kendi siparişlerini görebilir
            $orders = Order::with(['items'])
                ->where('user_id', $user->id)
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
        
        $orders = Order::with(['items'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
        
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
        
        $orders = Order::with(['items', 'items.product', 'items.product.images'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
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