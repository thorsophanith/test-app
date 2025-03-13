<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'shop'])->name('shop');
Route::get('/shop/{product_slug}', [ShopController::class, 'shop_details'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add_cart'])->name('cart.add');
Route::put('/cart/bok-cart/{rowId}', [CartController::class, 'bok_cart'])->name('cart.qty.bok');
Route::put('/cart/dok-cart/{rowId}', [CartController::class, 'dok_cart'])->name('cart.qty.dok');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_cart'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.clear');

Route::post('/cart/apply-to-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.apply.coupon');
Route::delete('/cart/remove-code', [CartController::class, 'remove_coupon_code'])->name('cart.remove.code');


Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'place_order'])->name('cart.place.order');
Route::get('/confirmation', [CartController::class, 'confirmation'])->name('cart.order.confirmation');


Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('wishlist');
Route::post('/wishlist/add', [WishlistController::class, 'add_wishlist'])->name('wishlist.add');
Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'remove_item'])->name('wishlist.remove');
Route::delete('/wishlist/clear', [WishlistController::class, 'empty_wishlist'])->name('wishlist.clear');
Route::post('/wishlist/move/{rowId}', [WishlistController::class, 'move_cart'])->name('wishlist.move.cart');


Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact-store', [HomeController::class, 'contact_store'])->name('contact.store');

Route::get('/home/search', [HomeController::class, 'search'])->name('home.search');

Route::get('/about', [HomeController::class, 'about'])->name('about');


Route::middleware(['auth'])->group(function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/order', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/order/{order_id}/details', [UserController::class, 'order_details'])->name('user.order.details');
    Route::put('/user/order/cancel-order', [UserController::class, 'concel_order'])->name('user.order.cancel');
});




Route::middleware(['auth', AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');     //     index
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');     //     brands
    Route::get('/admin/brand/add', [AdminController::class,'brand_add'])->name('admin.brand.add');     //     brand_add
    Route::post('/admin/brand/store', [AdminController::class,'brand_store'])->name('admin.brand.store');     //     brand_store
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');     //     brand_edit
    Route::put('/admin/brand/update', [AdminController::class,'brand_update'])->name('admin.brand.update');     //     brand_update
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');     //     brand_delete


    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');      // categories
    Route::get('/admin/categories/add', [AdminController::class, 'categories_add'])->name('admin.categories.add');      // categories_add
    Route::post('/admin/categories/store', [AdminController::class, 'categories_store'])->name('admin.categories.store');      // categories_store
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'categories_edit'])->name('admin.categories.edit');      // categories_edit
    Route::put('/admin/categories/update', [AdminController::class, 'categories_update'])->name('admin.categories.update');      // categories_update
    Route::delete('/admin/categories/{id}/delete', [AdminController::class, 'categories_delete'])->name('admin.categories.delete');      // categories_delete

    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/add', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::post('/admin/products/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/products/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/products/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.product.delete');

    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupons/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupons/store', [AdminController::class, 'coupon_store'])->name('admin.coupons.store');
    Route::get('/admin/coupons/edit/{id}', [AdminController::class, 'coupon_edit'])->name('admin.coupons.edit');
    Route::put('/admin/coupons/update', [AdminController::class, 'coupon_update'])->name('admin.coupons.update');
    Route::delete('/admin/coupons/{id}/delete', [AdminController::class, 'coupon_delete'])->name('admin.coupons.delete');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/{order_id}/order-details', [AdminController::class, 'order_details'])->name('admin.order.details');
    Route::put('/admin/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');

    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/slide-add', [AdminController::class, 'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/edit/{id}', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');

    Route::get('/admin/contact', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::delete('/admin/contact/{id}/delete', [AdminController::class, 'contact_delete'])->name('admin.contact.delete');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');

    Route::get('/admin/user', [AdminController::class, 'user'])->name('admin.user');

    Route::get('/admin/setting', [AdminController::class, 'setting'])->name('admin.setting');
});