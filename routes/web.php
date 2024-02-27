<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
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

Route::get('/', function () {
    return view('welcome');
});

//product
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/create', [ProductController::class, 'create']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::post('/products',[ProductController::class, 'store']);
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products/bulkdelete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
//variant product
Route::get('/products/variant/create' , [ProductVariationController::class, 'create']);
Route::post('/products/variant',[ProductVariationController::class, 'store']);


//categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/create', [CategoryController::class, 'create']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
