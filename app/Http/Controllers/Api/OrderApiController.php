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
    public function index()
    {
        // Yetki kontrolü
        if (!Auth::guard('admin')->check() && !Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz bulunmuyor.'
            ], 403);
        }
        
        // Admin kullanıcıları tüm siparişleri görebilir
        if (Auth::guard('admin')->check()) {
            $orders = Order::with(['items'])->latest()->paginate(20);
        } else {
            // Normal kullanıcılar sadece kendi siparişlerini görebilir
            $orders = Order::with(['items'])
                ->where('user_id', Auth::id())
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
    public function show($id)
    {
        $order = Order::with(['items', 'items.product'])->findOrFail($id);
        
        // Yetki kontrolü
        if (!Auth::guard('admin')->check() && (!Auth::check() || Auth::id() != $order->user_id)) {
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
    public function getUserOrders($userId)
    {
        // Yetki kontrolü
        if (!Auth::guard('admin')->check() && (!Auth::check() || Auth::id() != $userId)) {
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
        // Admin yetkisi kontrolü
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz bulunmuyor.'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled',
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
        // Admin yetkisi kontrolü
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için yetkiniz bulunmuyor.'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed',
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
} 