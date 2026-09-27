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

// Change Currency Route (USD / SYP)
Route::get('change-currency/{currency}', function ($currency) {
    if (in_array($currency, ['USD', 'SYP'])) {
        session()->put('currency', $currency);
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

// Legacy Profile Alias
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Storefront\AccountController::class, 'profile'])->name('profile.edit');
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
Route::get('/product/{id}', [\App\Http\Controllers\Storefront\ProductController::class, 'show'])->name('product.show');
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

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

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
    Route::resource('/products', ProductController::class);
    Route::get('/products-admin', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products-admin/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::get('/products-admin/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::delete('/products-admin/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

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
    Route::get('/orders-admin/create', [OrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/orders-admin', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::resource('/orders', OrderController::class);
    Route::get('/orders-admin', [OrderController::class, 'index'])->name('admin.orders.index');

    // Bundle Offers (Multi-products discount)
    Route::resource('/bundles', \App\Http\Controllers\Admin\BundleController::class);
    Route::get('/bundles-admin', [\App\Http\Controllers\Admin\BundleController::class, 'index'])->name('admin.bundles.index');
    Route::get('/bundles-admin/create', [\App\Http\Controllers\Admin\BundleController::class, 'create'])->name('admin.bundles.create');
    Route::get('/bundles-admin/{bundle}/edit', [\App\Http\Controllers\Admin\BundleController::class, 'edit'])->name('admin.bundles.edit');
    Route::delete('/bundles-admin/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'destroy'])->name('admin.bundles.destroy');
    Route::put('/bundles-admin/{bundle}', [\App\Http\Controllers\Admin\BundleController::class, 'update'])->name('admin.bundles.update');
    Route::post('/bundles-admin', [\App\Http\Controllers\Admin\BundleController::class, 'store'])->name('admin.bundles.store');

    // Single Deals & Discounts
    Route::resource('/deals', \App\Http\Controllers\Admin\DealController::class);
    Route::get('/deals-admin', [\App\Http\Controllers\Admin\DealController::class, 'index'])->name('admin.deals.index');

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
    Route::resource('/banners', BannerController::class);
    Route::get('/banners-admin', [BannerController::class, 'index'])->name('admin.banners.index');

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
    Route::resource('/users', UserController::class);
    Route::get('/users-admin', [UserController::class, 'index'])->name('admin.users.index');
});

require __DIR__ . '/auth.php';
