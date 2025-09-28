<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Public\MerchandisePublicController;
use App\Http\Controllers\Public\ArticlePublicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CatagorieController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\ArticleController;

// Login
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');

// --- Register (guest only) ---
Route::get('/register',  [\App\Http\Controllers\AuthController::class, 'showRegisterForm'])
    ->name('register')->middleware('guest');

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])
    ->name('register.store')->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// Public
Route::get('/', fn() => redirect()->route('shop.index'));
Route::get('/shop',      [MerchandisePublicController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [MerchandisePublicController::class, 'show'])->name('shop.show');
Route::get('/articles',      [ArticlePublicController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticlePublicController::class, 'show'])->name('articles.show');

// ⬇⬇ เลือกทางเดียว ⬇⬇
// A) เปรียบเทียบแบบ public
Route::get('/compare', [MerchandisePublicController::class, 'compare'])->name('shop.compare');

// B) ถ้าต้องล็อกอินเท่านั้น ให้คอมเมนต์ A แล้วใช้แบบนี้แทน
/*
Route::middleware('auth')->group(function () {
    Route::get('/compare', [MerchandisePublicController::class, 'compare'])->name('shop.compare');
});
*/

// Logged-in (User & Admin)
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/wishlist',         [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('users',       UserController::class)->except(['show']);
    Route::resource('categories',  CatagorieController::class)->except(['show']);
    Route::resource('merchandise', MerchandiseController::class)->except(['show']);
    Route::resource('articles',    ArticleController::class)->except(['show']);

    Route::delete('reviews/{id}', [ReviewController::class, 'adminDestroy'])->name('reviews.destroy');
});
