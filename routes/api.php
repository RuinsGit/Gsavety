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
use App\Http\Controllers\Api\HomeHeroApiController;
use App\Http\Controllers\Api\HomeCartSectionApiController;
use App\Http\Controllers\Api\HomeFeaturedBoxApiController;
use App\Http\Controllers\Api\HomeFollowApiController;
use App\Http\Controllers\Api\AboutApiController;
use App\Http\Controllers\Api\AboutCartSectionApiController;
use App\Http\Controllers\Api\AboutFeaturedBoxApiController;
use App\Http\Controllers\Api\AboutCenterCartApiController;
use App\Http\Controllers\Api\PartnerApiController;
use App\Http\Controllers\Api\AboutTextSectionApiController;
use App\Http\Controllers\Api\ServiceHeroApiController;
use App\Http\Controllers\Api\ContactHeroApiController;
use App\Http\Controllers\Api\ContactTitleApiController;
use App\Http\Controllers\Api\BlogBannerApiController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\PartnerBannerApiController;
use App\Http\Controllers\Api\HomeQuestionApiController;
use App\Http\Controllers\Api\ProductBannerApiController;
use App\Http\Controllers\Api\QuestionApiController;
use App\Http\Controllers\Api\SeoScriptController;

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

// SEO Script Routes
Route::prefix('seo-scripts')->group(function () {
    Route::get('/', [SeoScriptController::class, 'index']);
    Route::get('/{id}', [SeoScriptController::class, 'show']);
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

// Product Banner Routes
Route::prefix('product-banner')->group(function () {
    Route::get('/', [ProductBannerApiController::class, 'index']);
    Route::get('/{id}', [ProductBannerApiController::class, 'show']);
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

// Home Hero Routes
Route::prefix('home-heroes')->group(function () {
    Route::get('/', [HomeHeroApiController::class, 'index']);
    Route::get('/{id}', [HomeHeroApiController::class, 'show']);
});

// Home Cart Section Routes
Route::prefix('home-cart-sections')->group(function () {
    Route::get('/', [HomeCartSectionApiController::class, 'index']);
    Route::get('/{id}', [HomeCartSectionApiController::class, 'show']);
});

// Home Featured Box Routes
Route::prefix('home-featured-boxes')->group(function () {
    Route::get('/', [HomeFeaturedBoxApiController::class, 'index']);
    Route::get('/{id}', [HomeFeaturedBoxApiController::class, 'show']);
});

// Home Follow Routes
Route::prefix('home-follows')->group(function () {
    Route::get('/', [HomeFollowApiController::class, 'index']);
    Route::get('/{id}', [HomeFollowApiController::class, 'show']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/user-orders', [AuthController::class, 'getUserOrders']);
    Route::get('/user-cart', [App\Http\Controllers\Api\CartApiController::class, 'getUserCart']);
});

// User-specific protected routes
Route::middleware(['auth:sanctum', 'user'])->prefix('user')->group(function () {
    // Kullanıcı özel rotaları - sipariş, favori vs.
    // ...
});

// Admin protected routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Admin özel rotaları
    // ...
});

// Admin rotaları için gerekli middleware'i değiştirmiyoruz
// Admin routes middleware'i mevcut admin yapısıyla çalışacak şekilde kalıyor

// Sipariş API Rotaları
Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
    // Tüm siparişleri getir
    Route::get('/', [OrderApiController::class, 'index']);
    
    // Yeni sipariş oluştur
    Route::post('/', [OrderApiController::class, 'store']);
    
    // Giriş yapmış kullanıcının siparişlerini getir (token ile kimlik doğrulama)
    Route::get('/my-orders', [OrderApiController::class, 'getMyOrders']);
    
    // Belirli bir kullanıcının siparişlerini getir
    Route::get('/user/{userId}', [OrderApiController::class, 'getUserOrders']);
    
    // Sipariş durumunu güncelle
    Route::put('/{id}/status', [OrderApiController::class, 'updateStatus']);
    
    // Ödeme durumunu güncelle
    Route::put('/{id}/payment-status', [OrderApiController::class, 'updatePaymentStatus']);
    
    // Sipariş tipini güncelle (perakende/kurumsal)
    Route::put('/{id}/type', [OrderApiController::class, 'updateType']);
    
    // Perakende siparişleri listele (GET ve POST metodlarını destekler)
    Route::match(['get', 'post'], '/retail', [OrderApiController::class, 'getRetailOrders']);
    
    // Kurumsal siparişleri listele (GET ve POST metodlarını destekler)
    Route::match(['get', 'post'], '/corporate', [OrderApiController::class, 'getCorporateOrders']);
    
    // Belirli bir siparişin detayını getir (en sona aldık, çünkü /{id} formatı diğer rotalarla çakışabilir)
    Route::get('/{id}', [OrderApiController::class, 'show']);
});

// Sepet API Rotaları
Route::prefix('cart')->group(function () {
    // Sepet içeriğini görüntüle
    Route::get('/', [CartApiController::class, 'index']);
    
    // Sepete ürün ekle
    Route::post('/add', [CartApiController::class, 'addToCart']);
    
    // Sepete toplu ürün ekle
    Route::post('/add-multiple', [CartApiController::class, 'addMultipleToCart']);
    
    // Sepetten ürün çıkar
    Route::post('/remove', [CartApiController::class, 'removeFromCart']);
    
    // Sepetteki ürün miktarını güncelle
    Route::post('/update', [CartApiController::class, 'updateCartItem']);
    
    // Sepeti boşalt
    Route::post('/clear', [CartApiController::class, 'clearCart']);
    
    // Ürün ID'sine göre sepetten temizle
    Route::post('/clear-product', [CartApiController::class, 'clearCartByProductId']);
    
    // Ürün özelliklerine göre sepetten temizle
    Route::post('/clear-product-attributes', [CartApiController::class, 'clearCartByProductAttributes']);
});

// Checkout API rotası
Route::post('/checkout', [CheckoutApiController::class, 'checkout'])->middleware('auth:sanctum');

// Ürün API Rotaları
Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::get('/featured', [ProductApiController::class, 'featured']);
    Route::get('/search', [ProductApiController::class, 'search']);
    Route::get('/filters', [ProductApiController::class, 'getFilterOptions']);
    Route::get('/sort-by-age', [ProductApiController::class, 'sortByAge']);
    Route::get('/{productId}/colors', [ProductApiController::class, 'getProductColors']);
    Route::get('/{productId}/sizes', [ProductApiController::class, 'getProductSizes']);
    Route::get('/{productId}/stocks', [ProductApiController::class, 'getProductStocks']);
    Route::get('/{productId}/details', [ProductApiController::class, 'getProductDetails']);
    // Belirli bir ürünün detayını getir (en sona koyuyoruz çakışma olmaması için)
    Route::get('/{id}', [ProductApiController::class, 'show']);
});

// Home Question Routes
Route::prefix('home-questions')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\HomeQuestionApiController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\Api\HomeQuestionApiController::class, 'show']);
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

// About Routes
Route::get('/about', [AboutApiController::class, 'index']);

// About Cart Section Routes
Route::get('/about-cart-section', [AboutCartSectionApiController::class, 'index']);

// About Featured Box Routes
Route::prefix('about-featured-boxes')->group(function () {
    Route::get('/', [AboutFeaturedBoxApiController::class, 'index']);
    Route::get('/{id}', [AboutFeaturedBoxApiController::class, 'show']);
});

// About Center Cart Routes
Route::get('/about-center-cart', [AboutCenterCartApiController::class, 'index']);

// About Text Section Routes
Route::get('/about-text-section', [AboutTextSectionApiController::class, 'index']);

// Blog Banner Routes
Route::prefix('blog-banners')->group(function () {
    Route::get('/', [BlogBannerApiController::class, 'index']);
    Route::get('/{id}', [BlogBannerApiController::class, 'show']);
});

// Blog Routes
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogApiController::class, 'index']);
    Route::get('/{id}', [BlogApiController::class, 'show']);
    Route::get('/slug/{slug}', [BlogApiController::class, 'showBySlug']);
});

// Partner Routes
Route::prefix('partners')->group(function () {
    Route::get('/', [PartnerApiController::class, 'index']);
    Route::get('/{id}', [PartnerApiController::class, 'show']);
});

// Servis Hero rotaları
Route::prefix('service-heroes')->group(function () {
    Route::get('/', [ServiceHeroApiController::class, 'index']);
    Route::get('/{id}', [ServiceHeroApiController::class, 'show']);
});

// Contact Hero rotaları
Route::prefix('contact-heroes')->group(function () {
    Route::get('/', [ContactHeroApiController::class, 'index']);
    Route::get('/{id}', [ContactHeroApiController::class, 'show']);
});

// Contact Title rotaları
Route::prefix('contact-titles')->group(function () {
    Route::get('/', [ContactTitleApiController::class, 'index']);
    Route::get('/{id}', [ContactTitleApiController::class, 'show']);
});

// Partner Banner Routes
Route::prefix('partner-banners')->group(function () {
    Route::get('/', [PartnerBannerApiController::class, 'index']);
    Route::get('/{id}', [PartnerBannerApiController::class, 'show']);
});

// Contact Request Routes
Route::prefix('contact-requests')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\ContactRequestApiController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\Api\ContactRequestApiController::class, 'show']);
    Route::post('/', [App\Http\Controllers\Api\ContactRequestApiController::class, 'store']);
    Route::post('/{id}/toggle-status', [App\Http\Controllers\Api\ContactRequestApiController::class, 'updateStatus']);
    Route::delete('/{id}', [App\Http\Controllers\Api\ContactRequestApiController::class, 'destroy']);
});

// Question Routes
Route::prefix('questions')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\QuestionApiController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\Api\QuestionApiController::class, 'show']);
});
