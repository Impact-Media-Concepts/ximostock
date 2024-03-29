<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
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
Route::get('/products', [ProductController::class, 'index'])->middleware('auth');
Route::get('/products/create', [ProductController::class, 'create'])->middleware('can:create-product');
Route::get('/products/{product}', [ProductController::class, 'show'])->middleware('can:view-product,product');
Route::post('/products', [ProductController::class, 'store'])->middleware('can:create-product');
Route::patch('/products/{product}', [ProductController::class, 'update'])->middleware('can:update-product,product');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('can:destroy-product,product');
Route::post('/products/bulkdelete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
Route::post('/products/bulkdiscount', [ProductController::class, 'bulkDiscount'])->name('products.bulkDiscount');
Route::post('/products/bulklinksaleschannel', [ProductController::class, 'bulkLinkSalesChannel'])->name('products.bulkLinkSalesChannel');
Route::post('/products/bulkunlinksaleschannel', [ProductController::class, 'bulkUnlinkSalesChannel'])->name('products.bulkUnlinkSalesChannel');
Route::post('/products/bulkenablebackorders', [ProductController::class, 'bulkEnableBackorders'])->name('products.bulkEnableBackorders');
Route::post('/products/bulkdisablebackorders', [ProductController::class, 'bulkDisableBackorders'])->name('products.bulkDisableBackorders');
Route::post('/products/bulkenablecommunicateStock', [ProductController::class, 'bulkEnableCommunicateStock'])->name('products.bulkEnableCommunicateStock');
Route::post('/products/bulkdisablecommunicateStock', [ProductController::class, 'bulkDisableCommunicateStock'])->name('products.bulkDisableCommunicateStock');
//variant product
Route::get('/products/variant/create', [ProductVariationController::class, 'create']);
Route::post('/products/variant', [ProductVariationController::class, 'store']);



//categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/create', [CategoryController::class, 'create']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::patch('/categories/{category}', [CategoryController::class, 'update']);

//properties
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{property}', [PropertyController::class, 'show']);
Route::patch('/properties/{property}', [PropertyController::class, 'update']);
Route::post('/properties/bulkdelete', [PropertyController::class, 'bulkDelete']);

//authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
