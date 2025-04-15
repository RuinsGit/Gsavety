<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogoApiController;
use App\Http\Controllers\Api\TranslationManageController;
use App\Http\Controllers\Api\SeoController;
use App\Http\Controllers\Api\SocialMediaApiController;
use App\Http\Controllers\Api\SocialshareApiController;
use App\Http\Controllers\Api\SocialfooterApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CheckoutApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Logo Routes
Route::get('/logos', [LogoApiController::class, 'index']);
Route::get('/logos/{id}', [LogoApiController::class, 'show']);
Route::get('/logos/key/{key}', [LogoApiController::class, 'getByKey']);
Route::get('/logos/group/{group}', [LogoApiController::class, 'getByGroup']);

// Translation Routes
Route::get('translations', [TranslationManageController::class, 'index']);
Route::get('translations/{id}', [TranslationManageController::class, 'show']);
Route::get('translations/key/{key}', [TranslationManageController::class, 'getByKey']);
Route::get('translations/group/{group}', [TranslationManageController::class, 'getByGroup']);

// SEO Routes
Route::prefix('seo')->group(function () {
    Route::get('/', [SeoController::class, 'index']);
    Route::get('/{key}', [SeoController::class, 'show']);
    Route::post('/', [SeoController::class, 'store']);
    Route::put('/{id}', [SeoController::class, 'update']);
    Route::delete('/{id}', [SeoController::class, 'destroy']);
});

// Social Media Routes
Route::prefix('social-media')->group(function () {
    Route::get('/', [SocialMediaApiController::class, 'index']);
    Route::get('/{id}', [SocialMediaApiController::class, 'show']);
    Route::post('/', [SocialMediaApiController::class, 'store']);
    Route::put('/{id}', [SocialMediaApiController::class, 'update']);
    Route::delete('/{id}', [SocialMediaApiController::class, 'destroy']);
    Route::post('/{id}/toggle-status', [SocialMediaApiController::class, 'toggleStatus']);
});

// Socialshare Routes
Route::prefix('socialshares')->group(function () {
    Route::get('/', [SocialshareApiController::class, 'index']);
    Route::get('/{id}', [SocialshareApiController::class, 'show']);
    Route::post('/', [SocialshareApiController::class, 'store']);
    Route::put('/{id}', [SocialshareApiController::class, 'update']);
    Route::delete('/{id}', [SocialshareApiController::class, 'destroy']);
});

// Social Footer Routes
Route::prefix('social-footer')->group(function () {
    Route::get('/', [SocialfooterApiController::class, 'index']);
    Route::get('/{id}', [SocialfooterApiController::class, 'show']);
    Route::post('/', [SocialfooterApiController::class, 'store']);
    Route::put('/{id}', [SocialfooterApiController::class, 'update']);
    Route::delete('/{id}', [SocialfooterApiController::class, 'destroy']);
});

// Contact Routes
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactApiController::class, 'index']);
    Route::get('/{id}', [ContactApiController::class, 'show']);
    Route::post('/', [ContactApiController::class, 'store']);
    Route::put('/{id}', [ContactApiController::class, 'update']);
    Route::delete('/{id}', [ContactApiController::class, 'destroy']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// User-specific protected routes
Route::middleware(['auth:sanctum', 'user'])->prefix('user')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'me']);
    
    // Diğer kullanıcı API rotaları buraya eklenebilir
    // ...
});



// Admin rotaları için gerekli middleware'i değiştirmiyoruz
// Admin routes middleware'i mevcut admin yapısıyla çalışacak şekilde kalıyor

// Sipariş API Rotaları
Route::prefix('orders')->group(function () {
    // Tüm siparişleri getir
    Route::get('/', [OrderApiController::class, 'index']);
    
    // Yeni sipariş oluştur
    Route::post('/', [OrderApiController::class, 'store']);
    
    // Belirli bir siparişin detayını getir
    Route::get('/{id}', [OrderApiController::class, 'show']);
    
    // Belirli bir kullanıcının siparişlerini getir
    Route::get('/user/{userId}', [OrderApiController::class, 'getUserOrders']);
    
    // Sipariş durumunu güncelle
    Route::put('/{id}/status', [OrderApiController::class, 'updateStatus']);
    
    // Ödeme durumunu güncelle
    Route::put('/{id}/payment-status', [OrderApiController::class, 'updatePaymentStatus']);
});

// Sepet API Rotaları
Route::prefix('cart')->group(function () {
    // Sepet içeriğini görüntüle
    Route::get('/', [CartApiController::class, 'index']);
    
    // Sepete ürün ekle
    Route::post('/add', [CartApiController::class, 'addToCart']);
    
    // Sepetten ürün çıkar
    Route::post('/remove', [CartApiController::class, 'removeFromCart']);
    
    // Sepetteki ürün miktarını güncelle
    Route::post('/update', [CartApiController::class, 'updateCartItem']);
    
    // Sepeti boşalt
    Route::post('/clear', [CartApiController::class, 'clearCart']);
});

// Checkout API rotası
Route::post('/checkout', [CheckoutApiController::class, 'checkout']);

// Ürün API Rotaları
Route::prefix('products')->group(function () {
    // Tüm ürünleri getir (filtreleme ve sıralama destekli)
    Route::get('/', [ProductApiController::class, 'index']);
    
    // Öne çıkan ürünleri getir
    Route::get('/featured', [ProductApiController::class, 'featured']);
    
    // Ürün ara
    Route::get('/search', [ProductApiController::class, 'search']);
    
    // Belirli bir ürünün renklerini getir
    Route::get('/{productId}/colors', [ProductApiController::class, 'getProductColors']);
    
    // Belirli bir ürünün boyutlarını getir
    Route::get('/{productId}/sizes', [ProductApiController::class, 'getProductSizes']);
    
    // Belirli bir ürünün stok durumunu getir
    Route::get('/{productId}/stocks', [ProductApiController::class, 'getProductStocks']);
    
    // Belirli bir ürünün detayını getir (en sona koyuyoruz çakışma olmaması için)
    Route::get('/{id}', [ProductApiController::class, 'show']);
});

// Kategori API Rotaları
Route::prefix('categories')->group(function () {
    // Tüm kategorileri getir
    Route::get('/', [CategoryApiController::class, 'index']);
    
    // Belirli bir kategorinin detayını getir
    Route::get('/{id}', [CategoryApiController::class, 'show']);
    
    // Belirli bir kategoriye ait ürünleri getir
    Route::get('/{categoryId}/products', [CategoryApiController::class, 'getCategoryProducts']);
});

// Diğer API rotaları buraya eklenebilir