<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\{UserController, StockController, ProductController, CategoryController, CartController, CheckoutController, ClientController, ProfileController, AdminController, WishlistController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\WebsiteController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\SiteProductController;
use Illuminate\Http\Request;
use App\Http\Controllers\ClientAuth\LoginController;
use App\Http\Controllers\ClientAuth\RegisterController;
use App\Http\Controllers\Admin\BannerController;

// Phase 6: Static Pages & Storefront Features
Route::get('/offers', [\App\Http\Controllers\Storefront\OfferController::class, 'index'])->name('offers.index');
Route::get('/offers-deals', [\App\Http\Controllers\Storefront\OfferController::class, 'index'])->name('site.offers.index');
Route::get('/about', [\App\Http\Controllers\Storefront\PageController::class, 'about'])->name('pages.about');
Route::get('/about-us', [\App\Http\Controllers\Storefront\PageController::class, 'about'])->name('about');
Route::get('/branches', [\App\Http\Controllers\Storefront\PageController::class, 'branches'])->name('branches.index');

// Careers & Job Applications (Phase 6)
Route::get('/careers', [\App\Http\Controllers\Storefront\CareerController::class, 'index'])->name('careers.index');
Route::post('/careers', [\App\Http\Controllers\Storefront\CareerController::class, 'store'])->middleware('throttle:5,1')->name('careers.store');

// Contact Us & Messages (Phase 6)
Route::get('/contact', [\App\Http\Controllers\Storefront\ContactController::class, 'index'])->name('contact.index');
Route::get('/contact-us', [\App\Http\Controllers\Storefront\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Storefront\ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// Legal & Policies (Phase 6)
Route::get('/terms', [\App\Http\Controllers\Storefront\PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [\App\Http\Controllers\Storefront\PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/return-policy', [\App\Http\Controllers\Storefront\PageController::class, 'returnPolicy'])->name('pages.return-policy');
Route::get('/sitemap.xml', [\App\Http\Controllers\Storefront\PageController::class, 'sitemap'])->name('sitemap');

// Change Language Route
Route::get('change-language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        Session::put('locale', $lang);
        App::setLocale($lang);
    }
    return redirect()->back();
})->name('change.language');

// Change Currency Route (EGP / USD)
Route::get('change-currency/{currency}', function ($currency) {
    if (in_array(strtoupper($currency), ['EGP', 'USD'])) {
        session()->put('currency', strtoupper($currency));
    }
    return redirect()->back();
})->name('change.currency');

// Public Order Tracking (Phase 5 - No login required)
Route::get('/track/{order?}', [\App\Http\Controllers\Storefront\OrderController::class, 'track'])->name('orders.track');

// User Account, Addresses & Orders (Phase 5 - Protected by auth)
Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Storefront\AccountController::class, 'profile'])->name('account.profile');
    Route::put('/profile', [\App\Http\Controllers\Storefront\AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/password', [\App\Http\Controllers\Storefront\AccountController::class, 'updatePassword'])->name('account.password.update');
    
    // Saved Addresses
    Route::get('/addresses', [\App\Http\Controllers\Storefront\AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('/addresses', [\App\Http\Controllers\Storefront\AccountController::class, 'storeAddress'])->name('account.addresses.store');
    Route::delete('/addresses/{index}', [\App\Http\Controllers\Storefront\AccountController::class, 'deleteAddress'])->name('account.addresses.destroy');
    Route::patch('/addresses/{index}/default', [\App\Http\Controllers\Storefront\AccountController::class, 'setDefaultAddress'])->name('account.addresses.default');

    // My Orders
    Route::get('/orders', [\App\Http\Controllers\Storefront\OrderController::class, 'index'])->name('account.orders');
    Route::get('/orders/{id}', [\App\Http\Controllers\Storefront\OrderController::class, 'show'])->name('account.orders.show');
    Route::post('/orders/{id}/reorder', [\App\Http\Controllers\Storefront\OrderController::class, 'reorder'])->name('account.orders.reorder');
});

// Legacy Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Storefront Routes (Phase 3)
Route::get('/', [\App\Http\Controllers\Storefront\HomeController::class, 'index'])->name('site.home');

// Categories
Route::get('/categories', [\App\Http\Controllers\Storefront\CategoryController::class, 'index'])->name('categories.index');
Route::get('/product/category/{id}', [\App\Http\Controllers\Storefront\CategoryController::class, 'show'])->name('category.product');
Route::get('/category/{id}', [\App\Http\Controllers\Storefront\CategoryController::class, 'show'])->name('storefront.category.show');

// Products & Shop Catalog
Route::get('/site/products', [\App\Http\Controllers\Storefront\ProductController::class, 'shop'])->name('site.products');
Route::get('/product/shop', [\App\Http\Controllers\Storefront\ProductController::class, 'shop'])->name('product.shop');
Route::get('/shop', [\App\Http\Controllers\Storefront\ProductController::class, 'shop'])->name('shop');
Route::get('/products/details/{id}', [\App\Http\Controllers\Storefront\ProductController::class, 'show'])->name('product.details');
Route::get('/product/{slug}', [\App\Http\Controllers\Storefront\ProductController::class, 'show'])->name('product.show');
Route::get('/filter-products', [\App\Http\Controllers\Storefront\ProductController::class, 'shop'])->name('filter.products');

// Search & Live Autocomplete
Route::get('/search', [\App\Http\Controllers\Storefront\SearchController::class, 'index'])->name('search.index');
Route::get('/search/autocomplete', [\App\Http\Controllers\Storefront\SearchController::class, 'autocomplete'])->name('search.autocomplete');
Route::get('/search/ajax', [\App\Http\Controllers\Storefront\SearchController::class, 'autocomplete'])->name('search.ajax');

// Cart Routes (Phase 4)
Route::get('cart', [\App\Http\Controllers\Storefront\CartController::class, 'index'])->name('cart');
Route::get('cart/count', [\App\Http\Controllers\Storefront\CartController::class, 'getCount'])->name('cart.count');
Route::get('cart/payload', [\App\Http\Controllers\Storefront\CartController::class, 'getPayload'])->name('cart.payload');
Route::match(['get', 'post'], 'add-to-cart/{id}', [\App\Http\Controllers\Storefront\CartController::class, 'add'])->name('AddToCart');
Route::post('bundle/add-to-cart', [\App\Http\Controllers\Storefront\CartController::class, 'addBundle'])->name('bundle.add-to-cart');
Route::post('update-cart-quantity', [\App\Http\Controllers\Storefront\CartController::class, 'updateQuantity'])->name('update.cart.quantity');
Route::post('remove-from-cart', [\App\Http\Controllers\Storefront\CartController::class, 'removeItem'])->name('removefromcart');
Route::post('clear-cart', [\App\Http\Controllers\Storefront\CartController::class, 'clearCart'])->name('clearcart');
Route::post('cart/apply-coupon', [\App\Http\Controllers\Storefront\CartController::class, 'applyCoupon'])->middleware('throttle:coupon-apply')->name('cart.apply-coupon');
Route::post('cart/remove-coupon', [\App\Http\Controllers\Storefront\CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

// Product Reviews
Route::post('/reviews', [\App\Http\Controllers\Storefront\ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

// Checkout Flow (Phase 4)
Route::get('checkout', [\App\Http\Controllers\Storefront\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [\App\Http\Controllers\Storefront\CheckoutController::class, 'placeOrder'])->middleware('throttle:checkout')->name('checkout.place');
Route::get('checkout/confirmation/{order}', [\App\Http\Controllers\Storefront\CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// Dashboard Routes - Protected by auth & admin middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.index');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Real-time Notifications (AJAX)
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/feed', [\App\Http\Controllers\Admin\NotificationController::class, 'feed'])->name('admin.notifications.feed');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::delete('/notifications/clear-all', [\App\Http\Controllers\Admin\NotificationController::class, 'clearAll'])->name('admin.notifications.clear-all');

    // Products & Featured toggle
    Route::post('/products/{id}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('/products/{id}/admin-toggle-featured', [ProductController::class, 'toggleFeatured'])->name('admin.products.toggle-featured');
    Route::resource('/products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    Route::post('/products-store-alias', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products-update-alias/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/products-edit-alias/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products-destroy-alias/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products-admin', [ProductController::class, 'index'])->name('admin.products.legacy-index');
    Route::get('/products-admin/create', [ProductController::class, 'create'])->name('admin.products.legacy-create');
    Route::get('/products-admin/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.legacy-edit');
    Route::delete('/products-admin/{id}', [ProductController::class, 'destroy'])->name('admin.products.legacy-destroy');

    // Categories
    Route::resource('/categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    Route::get('/categories-admin', [CategoryController::class, 'index'])->name('admin.categories.legacy-index');
    Route::get('/categories-admin/create', [CategoryController::class, 'create'])->name('admin.categories.legacy-create');

    // Branches
    Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class)->names([
        'index' => 'admin.branches.index',
        'create' => 'admin.branches.create',
        'store' => 'admin.branches.store',
        'show' => 'admin.branches.show',
        'edit' => 'admin.branches.edit',
        'update' => 'admin.branches.update',
        'destroy' => 'admin.branches.destroy',
    ]);

    // Job Applications
    Route::get('/job-applications', [\App\Http\Controllers\Admin\JobApplicationController::class, 'index'])->name('admin.job-applications.index');
    Route::get('/job-applications/{id}', [\App\Http\Controllers\Admin\JobApplicationController::class, 'show'])->name('admin.job-applications.show');
    Route::put('/job-applications/{id}/status', [\App\Http\Controllers\Admin\JobApplicationController::class, 'updateStatus'])->name('admin.job-applications.update-status');
    Route::get('/job-applications/{id}/download-cv', [\App\Http\Controllers\Admin\JobApplicationController::class, 'downloadCv'])->name('admin.job-applications.download');
    Route::delete('/job-applications/{id}', [\App\Http\Controllers\Admin\JobApplicationController::class, 'destroy'])->name('admin.job-applications.destroy');

    // Contact Messages
    Route::get('/contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('admin.contact-messages.index');
    Route::get('/contact-messages/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('admin.contact-messages.show');
    Route::delete('/contact-messages/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');

    // Orders & Status Lifecycle
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('/orders/{id}/admin-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::get('/orders-admin/create', [OrderController::class, 'create'])->name('admin.orders.legacy-create');
    Route::post('/orders-admin', [OrderController::class, 'store'])->name('admin.orders.legacy-store');
    Route::resource('/orders', OrderController::class)->names([
        'index' => 'admin.orders.index',
        'create' => 'admin.orders.create',
        'store' => 'admin.orders.store',
        'show' => 'admin.orders.show',
        'edit' => 'admin.orders.edit',
        'update' => 'admin.orders.update',
        'destroy' => 'admin.orders.destroy',
    ]);
    Route::get('/orders-admin', [OrderController::class, 'index'])->name('admin.orders.legacy-index');
    Route::post('/orders-store-alias', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/orders-update-alias/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders-show-alias/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders-destroy-alias/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Bundle Offers (Multi-products discount)
    Route::resource('/bundles', \App\Http\Controllers\Admin\BundleController::class)->names([
        'index' => 'admin.bundles.index',
        'create' => 'admin.bundles.create',
        'store' => 'admin.bundles.store',
        'show' => 'admin.bundles.show',
        'edit' => 'admin.bundles.edit',
        'update' => 'admin.bundles.update',
        'destroy' => 'admin.bundles.destroy',
    ]);
    Route::get('/bundles-admin', [\App\Http\Controllers\Admin\BundleController::class, 'index'])->name('admin.bundles.legacy-index');
    Route::get('/bundles-admin/create', [\App\Http\Controllers\Admin\BundleController::class, 'create'])->name('admin.bundles.legacy-create');
    Route::get('/bundles-admin/{bundle}/edit', [\App\Http\Controllers\Admin\BundleController::class, 'edit'])->name('admin.bundles.legacy-edit');
    Route::delete('/bundles-admin/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'destroy'])->name('admin.bundles.legacy-destroy');
    Route::put('/bundles-admin/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'update'])->name('admin.bundles.legacy-update');
    Route::post('/bundles-admin', [\App\Http\Controllers\Admin\BundleController::class, 'store'])->name('admin.bundles.legacy-store');
    // Bundles legacy aliases
    Route::get('/bundles-alias', [\App\Http\Controllers\Admin\BundleController::class, 'index'])->name('bundles.index');
    Route::get('/bundles-create-alias', [\App\Http\Controllers\Admin\BundleController::class, 'create'])->name('bundles.create');
    Route::post('/bundles-store-alias', [\App\Http\Controllers\Admin\BundleController::class, 'store'])->name('bundles.store');
    Route::get('/bundles-show-alias/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'show'])->name('bundles.show');
    Route::get('/bundles-edit-alias/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'edit'])->name('bundles.edit');
    Route::put('/bundles-update-alias/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'update'])->name('bundles.update');
    Route::delete('/bundles-destroy-alias/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'destroy'])->name('bundles.destroy');

    // Single Deals & Discounts
    Route::resource('/deals', \App\Http\Controllers\Admin\DealController::class)->names([
        'index' => 'admin.deals.index',
        'create' => 'admin.deals.create',
        'store' => 'admin.deals.store',
        'show' => 'admin.deals.show',
        'edit' => 'admin.deals.edit',
        'update' => 'admin.deals.update',
        'destroy' => 'admin.deals.destroy',
    ]);
    Route::get('/deals-admin', [\App\Http\Controllers\Admin\DealController::class, 'index'])->name('admin.deals.legacy-index');
    // Deals legacy aliases
    Route::get('/deals-alias', [\App\Http\Controllers\Admin\DealController::class, 'index'])->name('deals.index');
    Route::get('/deals-create-alias', [\App\Http\Controllers\Admin\DealController::class, 'create'])->name('deals.create');
    Route::post('/deals-store-alias', [\App\Http\Controllers\Admin\DealController::class, 'store'])->name('deals.store');
    Route::get('/deals-show-alias/{deal}', [\App\Http\Controllers\Admin\DealController::class, 'show'])->name('deals.show');
    Route::get('/deals-edit-alias/{deal}', [\App\Http\Controllers\Admin\DealController::class, 'edit'])->name('deals.edit');
    Route::put('/deals-update-alias/{deal}', [\App\Http\Controllers\Admin\DealController::class, 'update'])->name('deals.update');
    Route::delete('/deals-destroy-alias/{deal}', [\App\Http\Controllers\Admin\DealController::class, 'destroy'])->name('deals.destroy');

    // Coupons & Vouchers Management
    Route::resource('/coupons', \App\Http\Controllers\Admin\CouponController::class)->names([
        'index' => 'admin.coupons.index',
        'create' => 'admin.coupons.create',
        'store' => 'admin.coupons.store',
        'edit' => 'admin.coupons.edit',
        'update' => 'admin.coupons.update',
        'destroy' => 'admin.coupons.destroy',
    ]);
    // Coupons legacy aliases
    Route::get('/coupons-alias', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons-create-alias', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons-store-alias', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons-edit-alias/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons-update-alias/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons-destroy-alias/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');

    // Weekly Offers Magazine Campaigns (Phase 7 - Unified System)
    Route::patch('/magazines/{magazine}/toggle-active', [\App\Http\Controllers\Admin\MagazineController::class, 'toggleActive'])->name('admin.magazines.toggle-active');
    Route::resource('/magazines', \App\Http\Controllers\Admin\MagazineController::class)->names([
        'index' => 'admin.magazines.index',
        'create' => 'admin.magazines.create',
        'store' => 'admin.magazines.store',
        'show' => 'admin.magazines.show',
        'edit' => 'admin.magazines.edit',
        'update' => 'admin.magazines.update',
        'destroy' => 'admin.magazines.destroy',
    ]);

    // Banners & Sliders
    Route::resource('/banners', BannerController::class)->names([
        'index' => 'admin.banners.index',
        'create' => 'admin.banners.create',
        'store' => 'admin.banners.store',
        'show' => 'admin.banners.show',
        'edit' => 'admin.banners.edit',
        'update' => 'admin.banners.update',
        'destroy' => 'admin.banners.destroy',
    ]);
    Route::get('/banners-admin', [BannerController::class, 'index'])->name('admin.banners.legacy-index');
    // Banners legacy aliases
    Route::get('/banners-alias', [BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners-create-alias', [BannerController::class, 'create'])->name('banners.create');
    Route::post('/banners-store-alias', [BannerController::class, 'store'])->name('banners.store');
    Route::get('/banners-edit-alias/{banner}', [BannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners-update-alias/{banner}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners-destroy-alias/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');

    // Global Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings-admin', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings-admin', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // WhatsApp Analytics & Clicks
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics-admin', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::delete('/analytics/{id}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'destroy'])->name('admin.analytics.destroy');
    Route::post('/analytics/bulk-destroy', [\App\Http\Controllers\Admin\AnalyticsController::class, 'bulkDestroy'])->name('admin.analytics.bulk-destroy');

    // Comprehensive Reports & Performance
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports-admin', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');

    // Abandoned Carts Follow-up
    Route::get('/abandoned-carts', [\App\Http\Controllers\Admin\AbandonedCartController::class, 'index'])->name('abandoned-carts.index');
    Route::get('/abandoned-carts-admin', [\App\Http\Controllers\Admin\AbandonedCartController::class, 'index'])->name('admin.abandoned-carts.index');
    Route::post('/abandoned-carts/{id}/recover', [\App\Http\Controllers\Admin\AbandonedCartController::class, 'markAsRecovered'])->name('abandoned-carts.recover');
    Route::post('/abandoned-carts-admin/{id}/recover', [\App\Http\Controllers\Admin\AbandonedCartController::class, 'markAsRecovered'])->name('admin.abandoned-carts.recover');
    Route::delete('/abandoned-carts/{id}', [\App\Http\Controllers\Admin\AbandonedCartController::class, 'destroy'])->name('abandoned-carts.destroy');
    Route::delete('/abandoned-carts-admin/{id}', [\App\Http\Controllers\Admin\AbandonedCartController::class, 'destroy'])->name('admin.abandoned-carts.destroy');

    // User Management
    Route::resource('/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::get('/users-admin', [UserController::class, 'index'])->name('admin.users.legacy-index');
    // Users legacy aliases
    Route::get('/users-alias', [UserController::class, 'index'])->name('users.index');
    Route::get('/users-create-alias', [UserController::class, 'create'])->name('users.create');
    Route::post('/users-store-alias', [UserController::class, 'store'])->name('users.store');
    Route::get('/users-edit-alias/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users-update-alias/{user}', [UserController::class, 'update'])->name('users.update');
    // Stocks & Inventory Management
    Route::resource('/stocks', StockController::class)->names([
        'index' => 'admin.stocks.index',
        'create' => 'admin.stocks.create',
        'store' => 'admin.stocks.store',
        'show' => 'admin.stocks.show',
        'edit' => 'admin.stocks.edit',
        'update' => 'admin.stocks.update',
        'destroy' => 'admin.stocks.destroy',
    ]);
    Route::get('/stocks-alias', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks-create-alias', [StockController::class, 'create'])->name('stocks.create');
    Route::post('/stocks-store-alias', [StockController::class, 'store'])->name('stocks.store');
    Route::get('/stocks-show-alias/{stock}', [StockController::class, 'show'])->name('stocks.show');
    Route::get('/stocks-edit-alias/{stock}', [StockController::class, 'edit'])->name('stocks.edit');
    Route::put('/stocks-update-alias/{stock}', [StockController::class, 'update'])->name('stocks.update');
    Route::delete('/stocks-destroy-alias/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');

    // Clients Management
    Route::resource('/clients', ClientController::class)->names([
        'index' => 'admin.clients.index',
        'create' => 'admin.clients.create',
        'store' => 'admin.clients.store',
        'show' => 'admin.clients.show',
        'edit' => 'admin.clients.edit',
        'update' => 'admin.clients.update',
        'destroy' => 'admin.clients.destroy',
    ]);
    Route::get('/clients-alias', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients-create-alias', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients-store-alias', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients-show-alias/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients-edit-alias/{client}', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients-update-alias/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients-destroy-alias/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});

require __DIR__ . '/auth.php';
