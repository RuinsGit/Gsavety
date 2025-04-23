<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CartController extends Controller
{
    /**
     * Tüm sepetlerin listesini görüntüler
     */
    public function index()
    {
        Artisan::call('migrate');
        $carts = Cart::with(['user', 'items.product'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
            
        return view('admin.carts.index', compact('carts'));
    }
    
    /**
     * Belirli bir sepetin detaylarını gösterir
     */
    public function show($id)
    {
        $cart = Cart::with(['user', 'items.product', 'items.color', 'items.size'])
            ->findOrFail($id);
        
        return view('admin.carts.show', compact('cart'));
    }
    
    /**
     * Belirli bir kullanıcının sepetlerini gösterir
     */
    public function userCarts($userId)
    {
        $user = User::findOrFail($userId);
        $carts = Cart::where('user_id', $userId)
            ->with(['items.product'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
            
        return view('admin.carts.user_carts', compact('carts', 'user'));
    }
    
    /**
     * Bir sepeti siler
     */
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->items()->delete();
        $cart->delete();
        
        return redirect()
            ->route('admin.carts.index')
            ->with('success', 'Sepet başarıyla silindi');
    }
    
    /**
     * Sepet istatistiklerini görüntüler
     */
    public function statistics()
    {
        $stats = [
            'total_carts' => Cart::count(),
            'active_carts' => Cart::where('is_active', true)->count(),
            'abandoned_carts' => Cart::where('is_active', true)
                ->where('updated_at', '<', now()->subDays(1))
                ->count(),
            'total_cart_value' => Cart::sum('total_amount'),
            'avg_cart_value' => Cart::where('total_amount', '>', 0)->avg('total_amount') ?? 0,
            'most_added_products' => DB::table('cart_items')
                ->select('product_id', DB::raw('count(*) as total'))
                ->groupBy('product_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get(),
        ];
        
        return view('admin.carts.statistics', compact('stats'));
    }
}
