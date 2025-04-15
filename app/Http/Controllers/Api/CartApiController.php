<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartApiController extends Controller
{
    /**
     * Sepet içeriğini görüntüle
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($details['product_id']);
            if (!$product) continue;
            
            $color = null;
            $size = null;
            
            if (isset($details['color_id'])) {
                $color = ProductColor::find($details['color_id']);
            }
            
            if (isset($details['size_id'])) {
                $size = ProductSize::find($details['size_id']);
            }
            
            $price = $details['price'];
            $quantity = $details['quantity'];
            $subtotal = $price * $quantity;
            $total += $subtotal;
            
            $items[] = [
                'id' => $id,
                'product_id' => $product->id,
                'product_name' => $product->name_az,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'color' => $color ? [
                    'id' => $color->id,
                    'name' => $color->color_name_az
                ] : null,
                'size' => $size ? [
                    'id' => $size->id,
                    'name' => $size->size_name_az
                ] : null,
                'image' => $product->main_image ? asset($product->main_image) : null
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
                'item_count' => count($items)
            ]
        ]);
    }
    
    /**
     * Sepete ürün ekle
     */
    public function addToCart(Request $request)
    {
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
            return response()->json([
                'success' => false,
                'message' => 'Yeterli stok bulunmuyor'
            ], 400);
        }
        
        // Sepet işlemleri
        $cart = Session::get('cart', []);
        
        $price = $stock->price ?? ($product->discount_price ?: $product->price);
        
        $cartId = $product->id;
        if ($request->color_id) $cartId .= '_' . $request->color_id;
        if ($request->size_id) $cartId .= '_' . $request->size_id;
        
        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $request->quantity;
        } else {
            $cart[$cartId] = [
                'product_id' => $product->id,
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'price' => $price,
                'quantity' => $request->quantity
            ];
        }
        
        Session::put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Ürün sepete eklendi',
            'data' => [
                'cart_id' => $cartId,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name_az,
                    'price' => $price,
                    'image' => $product->main_image ? asset($product->main_image) : null
                ],
                'quantity' => $request->quantity,
                'cart_count' => count($cart)
            ]
        ]);
    }
    
    /**
     * Sepetten ürün çıkar
     */
    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->cart_id])) {
            unset($cart[$request->cart_id]);
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün sepetten kaldırıldı',
                'data' => [
                    'cart_count' => count($cart)
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Belirtilen ürün sepette bulunamadı'
        ], 404);
    }
    
    /**
     * Sepetteki ürün miktarını güncelle
     */
    public function updateCartItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $cart = Session::get('cart', []);
        
        if (!isset($cart[$request->cart_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Belirtilen ürün sepette bulunamadı'
            ], 404);
        }
        
        $cartItem = $cart[$request->cart_id];
        
        // Stok kontrolü
        $stock = null;
        
        if (isset($cartItem['color_id']) && isset($cartItem['size_id'])) {
            $stock = ProductStock::where('product_id', $cartItem['product_id'])
                ->where('product_color_id', $cartItem['color_id'])
                ->where('product_size_id', $cartItem['size_id'])
                ->where('status', 1)
                ->first();
        } elseif (isset($cartItem['color_id'])) {
            $stock = ProductStock::where('product_id', $cartItem['product_id'])
                ->where('product_color_id', $cartItem['color_id'])
                ->whereNull('product_size_id')
                ->where('status', 1)
                ->first();
        } elseif (isset($cartItem['size_id'])) {
            $stock = ProductStock::where('product_id', $cartItem['product_id'])
                ->whereNull('product_color_id')
                ->where('product_size_id', $cartItem['size_id'])
                ->where('status', 1)
                ->first();
        } else {
            $stock = ProductStock::where('product_id', $cartItem['product_id'])
                ->whereNull('product_color_id')
                ->whereNull('product_size_id')
                ->where('status', 1)
                ->first();
        }
        
        if (!$stock || $stock->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Yeterli stok bulunmuyor'
            ], 400);
        }
        
        $cart[$request->cart_id]['quantity'] = $request->quantity;
        Session::put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Ürün miktarı güncellendi',
            'data' => [
                'cart_id' => $request->cart_id,
                'quantity' => $request->quantity,
                'subtotal' => $cart[$request->cart_id]['price'] * $request->quantity
            ]
        ]);
    }
    
    /**
     * Sepeti boşalt
     */
    public function clearCart()
    {
        Session::forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Sepet boşaltıldı'
        ]);
    }
} 