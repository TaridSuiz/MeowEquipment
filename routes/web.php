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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryBrowseController;

//home page
Route::get('/dashboard',  [HomeController::class, 'index'])->name('home.index');
//product home page
Route::get('/detail/{id}',  [HomeController::class, 'detail']);
Route::get('/search',  [HomeController::class, 'searchProduct']);

/*
|--------------------------------------------------------------------------
| Auth (Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    // Register
    Route::get('/register',  [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Logout (ต้องล็อกอิน)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Public (Guest สามารถเข้าถึงได้)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('shop.index'));


Route::get('/category/{category_id}', [\App\Http\Controllers\CategoryBrowseController::class, 'show'])
  ->whereNumber('category_id')
  ->name('category.show');
Route::get('/shop',    [MerchandisePublicController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [MerchandisePublicController::class, 'show'])->name('shop.show');

Route::get('/articles',    [ArticlePublicController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticlePublicController::class, 'show'])->name('articles.show');

/*
|--------------------------------------------------------------------------
| Logged-in (Users & Admin)
|--------------------------------------------------------------------------
| หมายเหตุ: จากสcopeที่ตกลงกัน "เปรียบเทียบสินค้า" ต้องล็อกอิน
*/
Route::middleware('auth')->group(function () {
    // Reviews
    Route::post('/reviews',      [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Wishlist
    Route::get('/wishlist',         [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Compare (ต้องล็อกอิน)
    Route::post('/compare', [MerchandisePublicController::class, 'compare'])->name('shop.compare');

    // Profile (ของตัวเอง)
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Admin (หลังบ้านเท่านั้น)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');
  // ⬇⬇ เพิ่มสองเส้นนี้สำหรับแก้ role / password แบบด่วนในหน้าเดียว
    Route::put('users/{user}/role', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('users.role.update');
    Route::put('users/{user}/password-quick', [\App\Http\Controllers\UserController::class, 'updatePasswordQuick'])->name('users.password.quick');

    // Users (resource + reset password)
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('users/{user}/reset',  [UserController::class, 'editReset'])->name('users.reset.edit');
    Route::put('users/{user}/reset',  [UserController::class, 'updateReset'])->name('users.reset.update');

    // Categories / Merchandise / Articles
    Route::resource('categories',  CatagorieController::class)->except(['show']);
    Route::resource('merchandise', MerchandiseController::class)->except(['show']);
    Route::resource('articles',    ArticleController::class)->except(['show']);

    // Review moderation (admin)
    Route::delete('reviews/{id}', [ReviewController::class, 'adminDestroy'])->name('reviews.destroy');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


});

