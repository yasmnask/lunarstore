<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductDetailController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\GoogleAuthController;
use App\Http\Controllers\Client\MidtransController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Livewire\Client;
use App\Livewire\Client\Profile;
use App\Livewire\Admin;
use App\Livewire\Admin\MasterData;
use App\Livewire\Admin\MasterData\ProductData;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AdminGuestMiddleware;

// ====================
// Public Routes
// ====================
Route::get('/', function () {
  $componentClass = Auth::guard('web')->check()
    ? Client\Home::class
    : Client\Landing::class;

  return app()->call($componentClass);
});
Route::get('/aboutus', Client\Aboutus::class)->name('aboutus');

Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

// ====================
// Guest Routes
// ====================

// Client guest
Route::middleware('guest')->group(function () {
  Route::get('/login', Client\Auth\Login::class)->name('login');
  Route::get('/register', Client\Auth\Register::class)->name('register');

  // Google Auth
  Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
  Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->withoutMiddleware(['csrf']);
});

// Admin guest
Route::middleware([AdminGuestMiddleware::class])->group(function () {
  Route::get('/admin/login', Admin\Login::class)->name('admin.login');
});

// ====================
// Authenticated Client Routes
// ====================
Route::middleware('auth')->group(function () {
  Route::get('/catalog', Client\Catalog::class)->name('catalog');
  Route::get('/product/{id}', Client\ProductDetails::class)->name('product.details');

  Route::get('/cart', Client\CartManager::class)->name('cart');

  // Checkout routes
  Route::get('/checkout', Client\CheckoutManager::class)->name('checkout');
  Route::get('/order/confirmation/{code}', Client\OrderConfirmation::class)->name('order.confirmation');
  Route::get('/order/history', Client\OrderHistory::class)->name('orders');
  Route::get('/order/{code}', Client\OrderDetails::class)->name('order.details');

  Route::get('/profile/{username}', Profile\Index::class)->name('profile');
});

// ====================
// Authenticated Admin Routes
// ====================
Route::middleware([AdminMiddleware::class])->group(function () {
  Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\Dashboard::class)->name('dashboard');

    // MASTER DATA

    // ====================
    // USER ADMINS
    // ====================
    Route::get('/user_admin', MasterData\UserAdmins::class)->name('user_admin');
    Route::get('/user_admin/data', [UserAdminController::class, 'index'])->name('user_admin.data');

    // ====================
    // USERS
    // ====================
    Route::get('/users', Admin\MasterData\Users::class)->name('users');
    Route::get('/users/data', [UserController::class, 'index'])->name('users.data');

    // ====================
    // PRODUCT CATEGORIES
    // ====================
    Route::get('/categories', ProductData\Categories::class)->name('products.categories');
    Route::get('/categories/data', [ProductCategoryController::class, 'index'])->name('products.categories.data');

    // ====================
    // PRODUCTS
    // ====================
    Route::get('/products', ProductData\Products::class)->name('products');
    Route::get('/products/data', [ProductController::class, 'index'])->name('products.data');

    // ====================
    // PRODUCT TYPES
    // ====================
    Route::get('/product_types', ProductData\Types::class)->name('product_types');
    Route::get('/product_types/data', [ProductTypeController::class, 'index'])->name('product_types.data');

    // ====================
    // PRODUCT DETAILS
    // ====================
    Route::get('/product_details', ProductData\Details::class)->name('product_details');
    Route::get('/product_details/data', [ProductDetailController::class, 'index'])->name('product_details.data');

    // TRANSACTION MANAGER
    Route::get('/transactions', Admin\TransactionManager::class)->name('transactions');

    // DASHBOARD
    // API route for chart data
    Route::get('/dashboard/chart-data/{type}', [Admin\Dashboard::class, 'getChartData'])->name('dashboard.chart-data');
  });
});
