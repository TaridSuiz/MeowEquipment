<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CatagorieController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;

//home page
Route::get('/', [UserController::class, 'index']);




//admins crud
// Users Routes
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/adding', [UserController::class, 'adding'])->name('user.create');
Route::post('/user', [UserController::class, 'create'])->name('user.store');
Route::get('/user/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/remove/{id}', [UserController::class, 'remove'])->name('user.destroy');

// Reset password
Route::get('/user/reset/{id}', [UserController::class, 'reset'])->name('user.reset');
Route::put('/user/reset/{id}', [UserController::class, 'resetPassword'])->name('user.reset.update');

// Category Routes
Route::get('/category', [CatagorieController::class,'index'])->name('category.index');           // แสดงรายการ
Route::get('/category/create', [CatagorieController::class,'adding'])->name('category.create');  // ฟอร์มเพิ่ม
Route::post('/category', [CatagorieController::class,'create'])->name('category.store');         // บันทึกเพิ่มใหม่
Route::get('/category/{id}/edit', [CatagorieController::class,'edit'])->name('category.edit');   // ฟอร์มแก้ไข
Route::put('/category/{id}', [CatagorieController::class,'update'])->name('category.update');    // บันทึกแก้ไข
Route::delete('/category/{id}', [CatagorieController::class,'remove'])->name('category.destroy');// ลบ


// Merchandise Routes
Route::get('/merchandise', [MerchandiseController::class, 'index'])->name('merchandise.index');
Route::get('/merchandise/adding', [MerchandiseController::class, 'adding'])->name('merchandise.create');
Route::post('/merchandise', [MerchandiseController::class, 'create'])->name('merchandise.store');
Route::get('/merchandise/{id}', [MerchandiseController::class, 'edit'])->name('merchandise.edit');
Route::put('/merchandise/{id}', [MerchandiseController::class, 'update'])->name('merchandise.update');
Route::delete('/merchandise/remove/{id}', [MerchandiseController::class, 'remove'])->name('merchandise.destroy');



//review

Route::get('/reviews', [ReviewController::class,'index'])->name('reviews.index');
Route::get('/reviews/adding', [ReviewController::class,'adding'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class,'create'])->name('reviews.store');
Route::get('/reviews/{id}', [ReviewController::class,'edit'])->name('reviews.edit');
Route::put('/reviews/{id}', [ReviewController::class,'update'])->name('reviews.update');
Route::delete('/reviews/remove/{id}', [ReviewController::class,'remove'])->name('reviews.destroy');

//article
Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/adding', [ArticleController::class, 'adding'])->name('article.create');
Route::post('/article', [ArticleController::class, 'create'])->name('article.store');
Route::get('/article/{id}', [ArticleController::class, 'edit'])->name('article.edit');
Route::put('/article/{id}', [ArticleController::class, 'update'])->name('article.update');
Route::delete('/article/remove/{id}', [ArticleController::class, 'remove'])->name('article.destroy');


//wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::get('/wishlist/adding', [WishlistController::class, 'adding'])->name('wishlist.create');
Route::post('/wishlist', [WishlistController::class, 'create'])->name('wishlist.store');
Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.destroy');



//product crud
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/adding',  [ProductController::class, 'adding']);
Route::post('/product',  [ProductController::class, 'create']);
Route::get('/product/{id}',  [ProductController::class, 'edit']);
Route::put('/product/{id}',  [ProductController::class, 'update']);
Route::delete('/product/remove/{id}',  [ProductController::class, 'remove']);
Route::get('/product/reset/{id}',  [ProductController::class, 'reset']);
Route::put('/product/reset/{id}',  [ProductController::class, 'resetPassword']);


//Student crud
Route::get('/student', [StudentController::class, 'index']);
Route::get('/student/adding',  [StudentController::class, 'adding']);
Route::post('/student',  [StudentController::class, 'create']);
Route::get('/student/{id}',  [StudentController::class, 'edit']);
Route::put('/student/{id}',  [StudentController::class, 'update']);
Route::delete('/student/remove/{id}',  [StudentController::class, 'remove']);
Route::get('/student/reset/{id}',  [StudentController::class, 'reset']);
Route::put('/student/reset/{id}',  [StudentController::class, 'resetPassword']);

//test crud
Route::get('/test', [TestController::class, 'index']);
Route::get('/test/adding',  [TestController::class, 'adding']);
Route::post('/test',  [TestController::class, 'create']);
Route::get('/test/{id}',  [TestController::class, 'edit']);
Route::put('/test/{id}',  [TestController::class, 'update']);
Route::delete('/test/remove/{id}',  [TestController::class, 'remove']);