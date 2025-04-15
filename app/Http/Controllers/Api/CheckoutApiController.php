<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CheckoutApiController extends Controller
{
    /**
     * Sipariş oluşturma
     */
    public function checkout(Request $request)
    {
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
            'payment_method' => 'required|string|in:cash_on_delivery,credit_card,bank_transfer',
            'cart_items' => 'nullable|array' // İstek ile birlikte sepet verisi almak için (opsiyonel)
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Sepeti kontrol et - önce session'dan, sonra istekten
        $cart = Session::get('cart', []);
        
        // Eğer sepet boş ve istek içinde cart_items varsa, istek içindeki sepeti kullan
        if (empty($cart) && $request->has('cart_items') && is_array($request->cart_items)) {
            $cart = [];
            
            foreach ($request->cart_items as $item) {
                if (isset($item['product_id']) && isset($item['quantity'])) {
                    $cartId = $item['product_id'];
                    if (isset($item['color_id'])) $cartId .= '_' . $item['color_id'];
                    if (isset($item['size_id'])) $cartId .= '_' . $item['size_id'];
                    
                    $product = Product::find($item['product_id']);
                    if (!$product) continue;
                    
                    // Stok kontrolü ve fiyat alma
                    $stock = $this->getProductStock(
                        $item['product_id'], 
                        $item['color_id'] ?? null, 
                        $item['size_id'] ?? null
                    );
                    
                    if (!$stock) continue;
                    
                    $price = $item['price'] ?? ($stock->price ?? ($product->discount_price ?: $product->price));
                    
                    $cart[$cartId] = [
                        'product_id' => $item['product_id'],
                        'color_id' => $item['color_id'] ?? null,
                        'size_id' => $item['size_id'] ?? null,
                        'price' => $price,
                        'quantity' => $item['quantity']
                    ];
                }
            }
            
            // Yeni sepeti session'a kaydet
            Session::put('cart', $cart);
        }
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Sepetiniz boş, sipariş oluşturulamadı.'
            ], 400);
        }
        
        // Sepet içeriğini ve stok durumunu kontrol et
        $items = [];
        $totalAmount = 0;
        
        foreach ($cart as $cartId => $cartItem) {
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
                    'message' => $product->name_az . ' ürünü için yeterli stok bulunmuyor.'
                ], 400);
            }
            
            $color = isset($cartItem['color_id']) ? ProductColor::find($cartItem['color_id']) : null;
            $size = isset($cartItem['size_id']) ? ProductSize::find($cartItem['size_id']) : null;
            
            $price = $cartItem['price'];
            $quantity = $cartItem['quantity'];
            $subtotal = $price * $quantity;
            $totalAmount += $subtotal;
            
            $items[] = [
                'cart_id' => $cartId,
                'product_id' => $product->id,
                'product_name' => $product->name_az,
                'price' => $price,
                'quantity' => $quantity,
                'total' => $subtotal,
                'color_id' => $cartItem['color_id'] ?? null,
                'size_id' => $cartItem['size_id'] ?? null,
                'color_name' => $color ? $color->color_name_az : null,
                'size_name' => $size ? $size->size_name_az : null,
                'stock' => $stock
            ];
        }
        
        // Sipariş oluştur
        $order = new Order();
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->user_id = Auth::check() ? Auth::id() : null;
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
        $order->payment_method = $request->payment_method;
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
        
        // Sepeti temizle
        Session::forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Siparişiniz başarıyla oluşturuldu.',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method
            ]
        ]);
    }
    
    /**
     * Ürün stoğunu al
     * 
     * @param int $productId
     * @param int|null $colorId
     * @param int|null $sizeId
     * @return \App\Models\ProductStock|null
     */
    private function getProductStock($productId, $colorId = null, $sizeId = null)
    {
        $query = ProductStock::where('product_id', $productId)
            ->where('status', 1);
            
        if ($colorId && $sizeId) {
            $query->where('product_color_id', $colorId)
                  ->where('product_size_id', $sizeId);
        } elseif ($colorId) {
            $query->where('product_color_id', $colorId)
                  ->whereNull('product_size_id');
        } elseif ($sizeId) {
            $query->whereNull('product_color_id')
                  ->where('product_size_id', $sizeId);
        } else {
            $query->whereNull('product_color_id')
                  ->whereNull('product_size_id');
        }
        
        return $query->first();
    }
} 