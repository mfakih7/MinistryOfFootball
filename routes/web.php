<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartCouponController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderSuccessController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TrackOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/track-order', [TrackOrderController::class, 'index'])->name('track-order');
Route::post('/track-order', [TrackOrderController::class, 'lookup'])->name('track-order.lookup');

Route::get('/shipping-policy', [PolicyController::class, 'shipping'])->name('policy.shipping');
Route::get('/return-policy', [PolicyController::class, 'returns'])->name('policy.returns');
Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('policy.privacy');
Route::get('/terms', [PolicyController::class, 'terms'])->name('policy.terms');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartCouponController::class, 'apply'])->name('cart.coupon.apply');
Route::delete('/cart/coupon/remove', [CartCouponController::class, 'remove'])->name('cart.coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/order/success/{order_number}', [OrderSuccessController::class, 'show'])->name('order.success');
Route::get('/order/{order}/whatsapp', [OrderSuccessController::class, 'whatsapp'])->name('order.whatsapp');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
