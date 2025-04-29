<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TranslationManageController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\SocialshareController;
use App\Http\Controllers\Admin\SocialfooterController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductColorController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductStockController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeHeroController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\HomeFollowController;
use App\Http\Controllers\Admin\HomeCartSectionController;
use App\Http\Controllers\Admin\HomeFeaturedBoxController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AboutCartSectionController;
use App\Http\Controllers\Admin\AboutCenterCartController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\AboutFeaturedBoxController;
use App\Http\Controllers\Admin\PartnerBannerController;
use App\Http\Controllers\Admin\PartnerHeroController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogBannerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AboutTextSectionController;
use App\Http\Controllers\Admin\ServiceHeroController;
use App\Http\Controllers\Admin\ContactHeroController;
use App\Http\Controllers\Admin\ContactTitleController;
use App\Http\Controllers\Admin\HomeQuestionController;
use App\Http\Controllers\Admin\ProductBannerController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\QuestionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->guard('admin')->check()) {
            return redirect()->route('back.pages.index');
        }
        return redirect()->route('admin.login');
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('back.pages.index');
        }
        return redirect()->route('admin.login');
    });

    Route::get('login', [AdminController::class, 'showLoginForm'])->name('admin.login')->middleware('guest:admin');
    Route::post('login', [AdminController::class, 'login'])->name('handle-login');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('profile', function () {
            return view('back.admin.profile');
        })->name('admin.profile');

        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');

        Route::prefix('pages')->name('back.pages.')->group(function () {
            Route::get('index', [PageController::class, 'index'])->name('index');

            // Translation Management Routes
            Route::get('translation-manage', [TranslationManageController::class, 'index'])->name('translation-manage.index');
            Route::get('translation-manage/create', [TranslationManageController::class, 'create'])->name('translation-manage.create');
            Route::post('translation-manage', [TranslationManageController::class, 'store'])->name('translation-manage.store');
            Route::get('translation-manage/{translation}/edit', [TranslationManageController::class, 'edit'])->name('translation-manage.edit');
            Route::put('translation-manage/{translation}', [TranslationManageController::class, 'update'])->name('translation-manage.update');
            Route::delete('translation-manage/{translation}', [TranslationManageController::class, 'destroy'])->name('translation-manage.destroy');

             // SEO routes
             Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
             Route::get('seo/create', [SeoController::class, 'create'])->name('seo.create');
             Route::post('seo', [SeoController::class, 'store'])->name('seo.store');
             Route::get('seo/{id}/edit', [SeoController::class, 'edit'])->name('seo.edit');
             Route::put('seo/{id}', [SeoController::class, 'update'])->name('seo.update');
             Route::delete('seo/{id}', [SeoController::class, 'destroy'])->name('seo.destroy');
             Route::post('seo/toggle-status/{id}', [SeoController::class, 'toggleStatus'])->name('seo.toggle-status.post');
             Route::post('seo/toggle-status/{id}', [SeoController::class, 'toggleStatus'])->name('seo.toggle-status');

             // Logo routes
             Route::get('logos', [LogoController::class, 'index'])->name('logos.index');
             Route::get('logos/create', [LogoController::class, 'create'])->name('logos.create');
             Route::post('logos', [LogoController::class, 'store'])->name('logos.store');
             Route::get('logos/{id}', [LogoController::class, 'show'])->name('logos.show');
             Route::get('logos/{id}/edit', [LogoController::class, 'edit'])->name('logos.edit');
             Route::put('logos/{id}', [LogoController::class, 'update'])->name('logos.update');
             Route::delete('logos/{id}', [LogoController::class, 'destroy'])->name('logos.destroy');

             // Product Banner routes
             Route::resource('product-banner', ProductBannerController::class);
             Route::post('product-banner/toggle-status/{id}', [ProductBannerController::class, 'toggleStatus'])->name('product-banner.toggle-status');

            
             // Social Media routes
             Route::get('social', [SocialMediaController::class, 'index'])->name('social.index');
             Route::get('social/create', [SocialMediaController::class, 'create'])->name('social.create');
             Route::post('social', [SocialMediaController::class, 'store'])->name('social.store');
             Route::get('social/{id}/edit', [SocialMediaController::class, 'edit'])->name('social.edit');
             Route::put('social/{id}', [SocialMediaController::class, 'update'])->name('social.update');
             Route::delete('social/{id}', [SocialMediaController::class, 'destroy'])->name('social.destroy');
             Route::post('social/order', [SocialMediaController::class, 'order'])->name('social.order');
             Route::post('social/toggle-status/{id}', [SocialMediaController::class, 'toggleStatus'])->name('social.toggle-status');

              // Social Share routes
            Route::get('socialshare', [SocialshareController::class, 'index'])->name('socialshare.index');
            Route::get('socialshare/create', [SocialshareController::class, 'create'])->name('socialshare.create');
            Route::post('socialshare', [SocialshareController::class, 'store'])->name('socialshare.store');
            Route::get('socialshare/{id}/edit', [SocialshareController::class, 'edit'])->name('socialshare.edit');
            Route::put('socialshare/{id}', [SocialshareController::class, 'update'])->name('socialshare.update');
            Route::delete('socialshare/{id}', [SocialshareController::class, 'destroy'])->name('socialshare.destroy');
            Route::post('socialshare/order', [SocialshareController::class, 'order'])->name('socialshare.order');
            Route::post('socialshare/{id}/toggle-status', [SocialshareController::class, 'toggleStatus'])->name('socialshare.toggleStatus');

              // Social Footer routes
              Route::get('socialfooter', [SocialfooterController::class, 'index'])->name('socialfooter.index');
              Route::get('socialfooter/create', [SocialfooterController::class, 'create'])->name('socialfooter.create');
              Route::post('socialfooter', [SocialfooterController::class, 'store'])->name('socialfooter.store');
              Route::get('socialfooter/{id}/edit', [SocialfooterController::class, 'edit'])->name('socialfooter.edit');
              Route::put('socialfooter/{id}', [SocialfooterController::class, 'update'])->name('socialfooter.update');
              Route::delete('socialfooter/{id}', [SocialfooterController::class, 'destroy'])->name('socialfooter.destroy');
              Route::post('socialfooter/order', [SocialfooterController::class, 'order'])->name('socialfooter.order');
              Route::post('socialfooter/toggle-status/{id}', [SocialfooterController::class, 'toggleStatus'])->name('socialfooter.toggle-status');

              // Product routes
              Route::resource('products', ProductController::class);
              Route::post('products/toggle-status/{id}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
              Route::post('products/toggle-featured/{id}', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
              
              // Product Color routes
              Route::resource('product_colors', ProductColorController::class);
              Route::post('product_colors/toggle-status/{id}', [ProductColorController::class, 'toggleStatus'])->name('product_colors.toggle-status');
              
              // Product Size routes
              Route::resource('product_sizes', ProductSizeController::class);
              Route::post('product_sizes/toggle-status/{id}', [ProductSizeController::class, 'toggleStatus'])->name('product_sizes.toggle-status');
              
              // Product Image routes
              Route::resource('product_images', ProductImageController::class);
              Route::post('product_images/toggle-status/{id}', [ProductImageController::class, 'toggleStatus'])->name('product_images.toggle-status');
              Route::post('product_images/set-as-main/{id}', [ProductImageController::class, 'setAsMain'])->name('product_images.set-as-main');
              Route::get('product_images/get-colors-by-product/{productId}', [ProductImageController::class, 'getColorsByProduct'])->name('product_images.get-colors-by-product');
              
              // Product Stock routes
              Route::resource('product_stocks', ProductStockController::class);
              Route::post('product_stocks/toggle-status/{id}', [ProductStockController::class, 'toggleStatus'])->name('product_stocks.toggle-status');
              Route::get('product_stocks/get-colors-by-product/{productId}', [ProductStockController::class, 'getColorsByProduct'])->name('product_stocks.get-colors-by-product');
              Route::get('product_stocks/get-sizes-by-product/{productId}', [ProductStockController::class, 'getSizesByProduct'])->name('product_stocks.get-sizes-by-product');
              // Stock routes
              Route::get('product_stocks/{id}/add-movement', [ProductStockController::class, 'addMovement'])->name('product_stocks.add-movement');
              Route::post('product_stocks/{id}/store-movement', [ProductStockController::class, 'storeMovement'])->name('product_stocks.store-movement');

              // Category routes
              Route::resource('categories', CategoryController::class);
              Route::post('categories/toggle-status/{id}', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
              
              // Home Hero routes
              Route::resource('home-heroes', HomeHeroController::class);
              Route::post('home-heroes/toggle-status/{id}', [HomeHeroController::class, 'toggleStatus'])->name('home-heroes.toggle-status');

              // Partner routes
              Route::resource('partners', PartnerController::class);
              Route::post('partners/toggle-status/{id}', [PartnerController::class, 'toggleStatus'])->name('partners.toggle-status');

              // Partner-Banner routes
              Route::resource('partner-banners', PartnerBannerController::class);
              Route::post('partner-banners/toggle-status/{id}', [PartnerBannerController::class, 'toggleStatus'])->name('partner-banners.toggle-status');

              // Partner-Hero routes
              Route::resource('partner-heroes', PartnerHeroController::class);
              Route::post('partner-heroes/toggle-status/{id}', [PartnerHeroController::class, 'toggleStatus'])->name('partner-heroes.toggle-status');

              // Home Follow routes
              Route::resource('home-follows', HomeFollowController::class);
              Route::post('home-follows/toggle-status/{id}', [HomeFollowController::class, 'toggleStatus'])->name('home-follows.toggle-status');
              
              // Home Cart Section routes
              Route::resource('home-cart-sections', HomeCartSectionController::class);
              Route::post('home-cart-sections/toggle-status/{id}', [HomeCartSectionController::class, 'toggleStatus'])->name('home-cart-sections.toggle-status');
              
              // Home Featured Box routes
              Route::resource('home-featured-boxes', HomeFeaturedBoxController::class);
              Route::post('home-featured-boxes/toggle-status/{id}', [HomeFeaturedBoxController::class, 'toggleStatus'])->name('home-featured-boxes.toggle-status');

              // About Featured Box routes
              Route::resource('about-featured-boxes', AboutFeaturedBoxController::class);
              Route::post('about-featured-boxes/toggle-status/{id}', [AboutFeaturedBoxController::class, 'toggleStatus'])->name('about-featured-boxes.toggle-status');
              
              // About routes
              Route::resource('about', AboutController::class);
              Route::post('about/toggle-status/{id}', [AboutController::class, 'toggleStatus'])->name('about.toggle-status');
              
              // About Cart Section routes
              Route::resource('about-cart-sections', AboutCartSectionController::class);
              Route::post('about-cart-sections/toggle-status/{id}', [AboutCartSectionController::class, 'toggleStatus'])->name('about-cart-sections.toggle-status');

              // Contact Title routes
              Route::resource('contact-titles', ContactTitleController::class);
              Route::post('contact-titles/toggle-status/{id}', [ContactTitleController::class, 'toggleStatus'])->name('contact-titles.toggle-status');
              
               // Contact routes
            Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
            Route::get('contact/create', [ContactController::class, 'create'])->name('contact.create');
            Route::post('contact', [ContactController::class, 'store'])->name('contact.store');
            Route::get('contact/{id}/edit', [ContactController::class, 'edit'])->name('contact.edit');
            Route::put('contact/{id}', [ContactController::class, 'update'])->name('contact.update');
            Route::delete('contact/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');

            // About Center Cart routes
            Route::resource('about-center-cart', AboutCenterCartController::class);

            // Service Hero routes
            Route::resource('service-heroes', ServiceHeroController::class);
            Route::post('service-heroes/toggle-status/{id}', [ServiceHeroController::class, 'toggleStatus'])->name('service-heroes.toggle-status');

            
            Route::post('about-center-cart/toggle-status/{id}', [AboutCenterCartController::class, 'toggleStatus'])->name('about-center-cart.toggle-status');
            Route::post('/admin/about-center-cart/upload-image', [AboutCenterCartController::class, 'uploadImage'])->name('about-center-cart.upload-image');
            
            // Blog routes
            Route::resource('blog', BlogController::class);
            Route::post('blog/toggle-status/{id}', [BlogController::class, 'toggleStatus'])->name('blog.toggle-status');

 // About Text Section Routes
 Route::prefix('about-text-sections')->name('about-text-sections.')->group(function () {
    Route::get('/', [AboutTextSectionController::class, 'index'])->name('index');
    Route::put('/', [AboutTextSectionController::class, 'update'])->name('update');
    Route::post('/toggle-status', [AboutTextSectionController::class, 'toggleStatus'])->name('toggle-status');
});

            // Contact Hero routes
            Route::resource('contact-heroes', ContactHeroController::class);
            Route::post('contact-heroes/toggle-status/{id}', [ContactHeroController::class, 'toggleStatus'])->name('contact-heroes.toggle-status');

            // Blog Banner routes
            Route::resource('blog-banner', BlogBannerController::class);
            Route::post('blog-banner/toggle-status/{id}', [BlogBannerController::class, 'toggleStatus'])->name('blog-banner.toggle-status');

            // User routes
            Route::resource('users', UserController::class);
            Route::post('users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

            // Admin Sipariş Rotaları
            Route::prefix('orders')->name('orders.')->group(function () {
                Route::get('/', [OrderController::class, 'index'])->name('index');
                Route::get('/retail', [OrderController::class, 'retailOrders'])->name('retail');
                Route::get('/corporate', [OrderController::class, 'corporateOrders'])->name('corporate');
                Route::get('/export', [OrderController::class, 'export'])->name('export');
                Route::get('/{id}', [OrderController::class, 'show'])->name('show');
                Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
                Route::post('/{id}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
                Route::post('/{id}/update-type', [OrderController::class, 'updateType'])->name('update-type');
                Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
            });
            
            // Admin Sepet Rotaları
            Route::prefix('carts')->name('carts.')->group(function () {
                Route::get('/', [CartController::class, 'index'])->name('index');
                Route::get('/statistics', [CartController::class, 'statistics'])->name('statistics');
                Route::get('/user/{userId}', [CartController::class, 'userCarts'])->name('user');
                Route::get('/{id}', [CartController::class, 'show'])->name('show');
                Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
            });

                // HomeQuestion Routes
                Route::get('home-questions', [HomeQuestionController::class, 'index'])->name('home-questions.index');
                Route::get('home-questions/create', [HomeQuestionController::class, 'create'])->name('home-questions.create');
                Route::post('home-questions', [HomeQuestionController::class, 'store'])->name('home-questions.store');
                Route::get('home-questions/{id}/edit', [HomeQuestionController::class, 'edit'])->name('home-questions.edit');
                Route::put('home-questions/{id}', [HomeQuestionController::class, 'update'])->name('home-questions.update');
                Route::delete('home-questions/{id}', [HomeQuestionController::class, 'destroy'])->name('home-questions.destroy');
                Route::post('home-questions/{id}/toggle-status', [HomeQuestionController::class, 'toggleStatus'])->name('home-questions.toggleStatus');
                Route::post('home-questions/update-order', [HomeQuestionController::class, 'updateOrder'])->name('home-questions.updateOrder');

                // Contact Request Routes
                Route::get('contact-requests', [ContactRequestController::class, 'index'])->name('contact-requests.index');
                Route::get('contact-requests/{id}', [ContactRequestController::class, 'show'])->name('contact-requests.show');
                Route::delete('contact-requests/{id}', [ContactRequestController::class, 'destroy'])->name('contact-requests.destroy');
                Route::post('contact-requests/{id}/toggle-status', [ContactRequestController::class, 'toggleStatus'])->name('contact-requests.toggle-status');

                // Question Routes
                Route::get('questions', [App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('questions.index');
                Route::get('questions/create', [App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('questions.create');
                Route::post('questions', [App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('questions.store');
                Route::get('questions/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'show'])->name('questions.show');
                Route::get('questions/{id}/edit', [App\Http\Controllers\Admin\QuestionController::class, 'edit'])->name('questions.edit');
                Route::put('questions/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'update'])->name('questions.update');
                Route::delete('questions/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'destroy'])->name('questions.destroy');
                Route::post('questions/{id}/toggle-status', [App\Http\Controllers\Admin\QuestionController::class, 'toggleStatus'])->name('questions.toggle-status');

        });

       
    });
});
