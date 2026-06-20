<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageSlideController;
use App\Http\Controllers\Admin\LeagueController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\TeamController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('homepage-slides', HomepageSlideController::class)->except(['show']);
    Route::resource('coupons', CouponController::class)->except(['show']);

    Route::get('feedback', [ContactMessageController::class, 'index'])->name('feedback.index');
    Route::get('feedback/{message}', [ContactMessageController::class, 'show'])->name('feedback.show');
    Route::patch('feedback/{message}/read', [ContactMessageController::class, 'markRead'])->name('feedback.read');
    Route::delete('feedback/{message}', [ContactMessageController::class, 'destroy'])->name('feedback.destroy');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('leagues', LeagueController::class)->except(['show']);
    Route::resource('teams', TeamController::class)->except(['show']);
    Route::resource('product-types', ProductTypeController::class)->except(['show']);
    Route::resource('sizes', SizeController::class)->except(['show']);
    Route::resource('colors', ColorController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('orders/{order}/notes', [OrderController::class, 'updateNotes'])->name('orders.notes');

    Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
    Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::post('products/{product}/images/{image}/main', [ProductImageController::class, 'setMain'])->name('products.images.main');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('branding', [BrandingController::class, 'edit'])->name('branding.edit');
    Route::put('branding', [BrandingController::class, 'update'])->name('branding.update');
});
