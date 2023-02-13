<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\CustomerRegController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// FRONTEND CONTROLLER

Route::get('/', [FrontController::class, 'index'])->name('index');
Route::get('/categories/product/{category_id}', [FrontController::class, 'categories_product'])->name('categories.product');

Auth::routes();

// PROFILE SECTION

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update_profile'])->name('update.profile');
Route::get('/password', [ProfileController::class, 'password'])->name('password');
Route::post('/password', [ProfileController::class, 'update_password'])->name('update.password');
Route::get('/user/list', [ProfileController::class, 'user_list'])->name('users.list');
Route::get('/user/delete/{user_id}', [ProfileController::class, 'user_delete'])->name('user.delete');

Route::get('/touhidul/user/delete/{user_id}', [HomeController::class, 'user'])->name('user.dlt');


// HOME CONTROLLER

Route::get('/home', [HomeController::class, 'home'])->name('home');

//  CATEGORY SECTION

Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::post('/category/store', [CategoryController::class, 'category_store'])->name('category.store');
Route::get('/category/edit/{category_id}', [CategoryController::class, 'category_edit'])->name('category.edit');
Route::post('/category/update', [CategoryController::class, 'category_update'])->name('category.update');
Route::get('/category/delete/{category_id}', [CategoryController::class, 'category_dlt'])->name('category.dlt');

Route::get('/category/restore/{category_id}', [CategoryController::class, 'category_restore'])->name('category.restore');
Route::get('/category/permanently/delete/{category_id}', [CategoryController::class, 'dlt_trashed'])->name('dlt.trashed');

// SUBCATEGORY SECTION

Route::get('/subcategory', [SubcategoryController::class, 'subcategory'])->name('subcategory');
Route::post('/subcategory/store', [SubcategoryController::class, 'subcategory_store'])->name('subcategory.store');

Route::get('/subcategory/edit/{subcategory_id}', [SubcategoryController::class, 'subcategory_edit'])->name('subcategory.edit');
Route::post('/subcategory/update', [SubcategoryController::class, 'subcategory_update'])->name('subcategory.update');
Route::get('/subcategory/delete/{subcategory_id}', [SubcategoryController::class, 'subcategory_dlt'])->name('subcategory.dlt');

// PRODUCT SECTION

Route::get('/add/new/product', [ProductController::class, 'add_product'])->name('add.product');
Route::post('/product/store', [ProductController::class, 'product_store'])->name('product.store');

Route::post('/subcategory', [ProductController::class, 'get_subcategory']);

Route::get('/all/products', [ProductController::class, 'all_product'])->name('all.product');
Route::get('/variation', [ProductController::class, 'variation'])->name('variation');
Route::post('/variation/store', [ProductController::class, 'variation_store'])->name('variation.store');
Route::get('/inventory/{product_id}', [ProductController::class, 'inventory'])->name('inventory');
Route::post('/inventory/store', [ProductController::class, 'inventory_store'])->name('inventory.store');
Route::get('/product/delete/{product_id}', [ProductController::class, 'product_dlt'])->name('product.dlt');

// COUPON SECTION
Route::get('/add/coupon', [CouponController::class, 'add_coupon'])->name('add.coupon');
Route::post('/add/coupon', [CouponController::class, 'coupon'])->name('coupon');
Route::get('/delete/coupon/{coupon_id}', [CouponController::class, 'dlt_coupon'])->name('dlt.coupon');

// ORDERS

Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
Route::post('/order/status', [OrderController::class, 'order_status'])->name('order.status');

// PERMISSIONS Section

Route::get('/add/permission', [RoleController::class, 'add_permission'])->name('add.permission');
Route::post('/permission/store', [RoleController::class, 'permisson_store'])->name('permisson.store');
Route::post('/role/store', [RoleController::class, 'role_store'])->name('role.store');
Route::post('/assign/role', [RoleController::class, 'assign_role'])->name('assign.role');
Route::get('/remove/role/{user_id}', [RoleController::class, 'remove_role'])->name('remove.role');

Route::get('/edit/permission{role_id}', [RoleController::class, 'edit_permission'])->name('edit.permission');
Route::get('/delete/permission{role_id}', [RoleController::class, 'delete_permission'])->name('delete.permission');

// FRONT CONTYROLLER SECTION

Route::get('/cart', [FrontController::class, 'cart'])->name('cart');
Route::get('/shop', [FrontController::class, 'shop'])->name('shop');
Route::get('/product/{slug_id}', [FrontController::class, 'product'])->name('product');
Route::post('/getsize', [FrontController::class, 'getsize']);
Route::get('/customer/reg/login', [FrontController::class, 'customer_reg_login'])->name('customer.reglogin');

// Customer Section

// Route::post('/customer/registration', [CustomerRegisterController::class, 'customer_registration'])->name('customer.store');
Route::post('/customer/store', [CustomerRegController::class, 'customer_store'])->name('customer.store');
Route::post('/customer/login', [CustomerLoginController::class, 'customer_login'])->name('customer.login');
Route::get('/customer/logout', [CustomerLoginController::class, 'customer_logout'])->name('customer.logout');

// WISHLIST SECTION

Route::post('/add/wishlist', [WishlistController::class, 'add_wishlist'])->name('add.wishlist');
Route::get('/remove/wishlist/{wishlist_id}', [WishlistController::class, 'remove_wishlist'])->name('remove.wishlist');

// CART SECTION

Route::post('/add/cart', [CartController::class, 'add_cart'])->name('add.cart');
Route::get('/remove/cart/{cart_id}', [CartController::class, 'remove_cart'])->name('remove.cart');
Route::post('/cart/update', [CartController::class, 'update_cart'])->name('update.cart');

// CUSTOMER CONTROLLER SECTION

Route::get('/customer/profile', [CustomerController::class, 'customer_profile'])->name('customer.profile');
Route::post('/customer/profile/update', [CustomerController::class, 'customer_profile_update'])->name('customer.profile.update');
Route::get('/myorder', [CustomerController::class, 'myorder'])->name('myorder');

//CHECKOUT

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/getcountry', [CheckoutController::class, 'getcountry']);
Route::post('/checkout/store', [CheckoutController::class, 'checkout_store'])->name('checkout.store');

// SSLCOMMERZ Start

Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::get('/pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);

//SSLCOMMERZ END

// STRIPE PAYMENT SECTION

Route::controller(StripePaymentController::class)->group(function(){
    Route::get('stripe', 'stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');
});

// PRODUCT FILTERING

Route::get('/filtering', [SearchController::class, 'search'])->name('search');

// Password Reset Request section here

Route::get('/reset/request/form/password', [CustomerController::class, 'password_reset_request'])->name('reset.request.password');
Route::post('/reset/request/send', [CustomerController::class, 'reset_request_send'])->name('reset.request.send');
Route::get('/generate/new/pw/form/{token}', [CustomerController::class, 'generate_new_pw_form'])->name('generate.new.pw.form');
Route::post('/new/password/generate', [CustomerController::class, 'new_pw_generate'])->name('new.pw.generate');
Route::get('/confirm/verify/{token}', [CustomerController::class, 'confirm_verify'])->name('confirm.verify');

// SOCIAL LOGINS

// GITHOUB LOGIN

Route::get('/github/redirect', [GithubController::class, 'github_redirect'])->name('github.redirect');
Route::get('/github/callback', [GithubController::class, 'github_callback'])->name('github.callback');

// GOOGLE LOGIN

Route::get('/google/redirect', [GoogleController::class, 'google_redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'google_callback'])->name('google.callback');

// REVIEW SECTION

Route::post('/review/store', [FrontController::class, 'review_store'])->name('review.store');

// PayPal payment setup

Route::get('/paypal/payment', [PayPalController::class, 'paypal_payment'])->name('paypal.payment');
Route::get('/payment', [PayPalController::class, 'payment'])->name('payment');
Route::get('/cancel', [PayPalController::class, 'cancel'])->name('payment.cancel');
Route::get('/payment/success', [PayPalController::class, 'success'])->name('payment.success');
