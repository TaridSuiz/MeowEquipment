<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CatagorieController;


//home page
Route::get('/', [UserController::class, 'index']);




//admins crud
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/adding',  [UserController::class, 'adding']);
Route::post('/user',  [UserController::class, 'create']);
Route::get('/user/{id}',  [UserController::class, 'edit']);
Route::put('/user/{id}',  [UserController::class, 'update']);
Route::delete('/user/remove/{id}',  [UserController::class, 'remove']);
// web.php
Route::get('/user/reset/{id}', [UserController::class, 'reset'])->name('user.reset');
Route::put('/user/reset/{id}', [UserController::class, 'resetPassword'])->name('user.reset.update');


Route::get('/category', [CatagorieController::class,'index'])->name('category.index');
Route::get('/category/adding', [CatagorieController::class,'adding'])->name('category.create');
Route::post('/category', [CatagorieController::class,'create'])->name('category.store');
Route::get('/category/{id}', [CatagorieController::class,'edit'])->name('category.edit');
Route::put('/category/{id}', [CatagorieController::class,'update'])->name('category.update');
Route::delete('/category/remove/{id}', [CatagorieController::class,'remove'])->name('category.destroy');




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