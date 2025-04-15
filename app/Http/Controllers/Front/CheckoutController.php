<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        // Sepeti kontrol et
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('front.cart')->with('error', 'Sepetiniz boş, lütfen ürün ekleyin.');
        }
        
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::findOrFail($details['product_id']);
            $color = null;
            $size = null;
            
            if (isset($details['color_id'])) {
                $color = ProductColor::find($details['color_id']);
            }
            
            if (isset($details['size_id'])) {
                $size = ProductSize::find($details['size_id']);
            }
            
            $cartItems[] = [
                'id' => $id,
                'product' => $product,
                'quantity' => $details['quantity'],
                'color' => $color,
                'size' => $size,
                'price' => $details['price'],
                'total' => $details['price'] * $details['quantity']
            ];
            
            $total += $details['price'] * $details['quantity'];
        }
        
        return view('front.checkout', compact('cartItems', 'total'));
    }
    
    public function store(Request $request)
    {
        // Form doğrulama
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'comment' => 'nullable|string',
            'payment_method' => 'required|string|in:cash_on_delivery'
        ]);
        
        // Sepeti kontrol et
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('front.cart')->with('error', 'Sepetiniz boş, lütfen ürün ekleyin.');
        }
        
        $total = 0;
        
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }
        
        // Yeni sipariş oluştur
        $order = new Order();
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->user_id = auth()->check() ? auth()->id() : null;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->postal_code = null;
        $order->country = 'Azerbaijan';
        $order->comment = $request->comment;
        $order->total_amount = $total;
        $order->status = 'pending';
        $order->payment_status = 'pending';
        $order->payment_method = 'cash_on_delivery';
        $order->save();
        
        // Sipariş ürünlerini ekle
        foreach ($cart as $id => $details) {
            $product = Product::findOrFail($details['product_id']);
            
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $details['product_id'];
            $orderItem->product_name = $product->name_az;
            $orderItem->quantity = $details['quantity'];
            $orderItem->price = $details['price'];
            $orderItem->total = $details['price'] * $details['quantity'];
            
            if (isset($details['color_id'])) {
                $color = ProductColor::find($details['color_id']);
                $orderItem->color_id = $details['color_id'];
                $orderItem->color_name = $color ? $color->color_name_az : null;
            }
            
            if (isset($details['size_id'])) {
                $size = ProductSize::find($details['size_id']);
                $orderItem->size_id = $details['size_id'];
                $orderItem->size_name = $size ? $size->size_name_az : null;
            }
            
            $orderItem->save();
        }
        
        // Sepeti temizle
        Session::forget('cart');
        
        // Teşekkür sayfasına yönlendir
        return redirect()->route('front.checkout.success', ['order' => $order->id])->with('success', 'Siparişiniz başarıyla oluşturuldu.');
    }
    
    public function success($order)
    {
        $order = Order::with('items')->findOrFail($order);
        return view('front.checkout_success', compact('order'));
    }
} 