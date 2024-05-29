<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryLocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SalesChannelController;
use App\Http\Controllers\UserController;
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
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->middleware('can:viewAny,App\Models\Product');
    Route::get('/products/create', [ProductController::class, 'create'])->middleware('can:create,App\Models\Product');
    Route::get('/products/export/', [ProductController::class, 'export']);//export
    Route::get('/products/archive',[ProductController::class, 'archive'])->middleware('can:forceDelete,App\Models\Product')->middleware('can:restore,App\Models\Product');
    Route::get('/products/{product}', [ProductController::class, 'show'])->middleware('can:view,product');
    Route::post('/products', [ProductController::class, 'store']);//autherized in controller
    Route::patch('/products/{product}', [ProductController::class, 'update']);//autherized in controller
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('can:delete,product');
    Route::post('/products/bulkdelete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');//autherized in controller
    Route::post('/products/bulkdiscount', [ProductController::class, 'bulkDiscount'])->name('products.bulkDiscount');//autherized in controller
    Route::post('/products/bulkdiscountforce', [ProductController::class, 'bulkDiscountForce']);//autherized in controller
    Route::post('/products/bulklinksaleschannel', [ProductController::class, 'bulkLinkSalesChannel'])->name('products.bulkLinkSalesChannel');//autherized in controller
    Route::post('/products/bulkunlinksaleschannel', [ProductController::class, 'bulkUnlinkSalesChannel'])->name('products.bulkUnlinkSalesChannel');//autherized in controller
    Route::post('/products/bulkenablebackorders', [ProductController::class, 'bulkEnableBackorders'])->name('products.bulkEnableBackorders');//autherized in controller
    Route::post('/products/bulkdisablebackorders', [ProductController::class, 'bulkDisableBackorders'])->name('products.bulkDisableBackorders');//autherized in controller
    Route::post('/products/bulkenablecommunicateStock', [ProductController::class, 'bulkEnableCommunicateStock'])->name('products.bulkEnableCommunicateStock');//autherized in controller
    Route::post('/products/bulkdisablecommunicateStock', [ProductController::class, 'bulkDisableCommunicateStock'])->name('products.bulkDisableCommunicateStock');//autherized in controller
    Route::post('/products/restore', [ProductController::class, 'restore'])->middleware('can:restore,App\Models\Product');
    Route::post('/products/forcedelete', [ProductController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\Product');

    //variant product
    Route::get('/products/variant/create', [ProductVariationController::class, 'create'])->middleware('can:create,App\Models\Product');
    Route::post('/products/variant', [ProductVariationController::class, 'store']);//autherized in controller

    //categories
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('can:viewAny,App\Models\Category');
    Route::get('/categories/create', [CategoryController::class, 'create'])->middleware('can:create,App\Models\Category');
    Route::get('/categories/archive', [CategoryController::class, 'archive'])->middleware('can:restore,App\Models\categories')->middleware('can:forceDelete,App\Models\categories');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->middleware('can:view,category');
    Route::post('/categories', [CategoryController::class, 'store']);//autherized in controller
    Route::patch('/categories/{category}', [CategoryController::class, 'update']);//autherized in controller
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('can:delete,category');
    Route::post('/categories/restore',[CategoryController::class, 'restore'])->middleware('can:restore,App\Models\categories');
    Route::post('/categories/forcedelete',[CategoryController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\categories');

    //properties
    Route::get('/properties', [PropertyController::class, 'index'])->middleware('can:viewAny,App\Models\Property');
    Route::get('/properties/create', [PropertyController::class, 'create'])->middleware('can:create,App\Models\Property');
    Route::get('/properties/archive',[PropertyController::class, 'archive'])->middleware('can:restore,App\Models\Property')->middleware('can:forceDelete,App\Models\Property');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->middleware('can:view,property');
    Route::post('/properties',[PropertyController::class, 'store'])->middleware('can:create,App\Models\Property');
    Route::patch('/properties/{property}', [PropertyController::class, 'update'])->middleware('can:update,property');
    Route::post('/properties/bulkdelete', [PropertyController::class, 'bulkDelete']);//autherized in controller
    Route::post('/properties/restore',[PropertyController::class, 'restore'])->middleware('can:restore,App\Models\Property');
    Route::post('/properties/forcedelete',[PropertyController::class, 'forceDelete'])->middleware('can:foreceDelete,App\Models\Property');

    //salesChannels
    Route::get('/saleschannels', [SalesChannelController::class, 'index'])->middleware('can:viewAny,App\Models\SalesChannel');
    Route::get('/saleschannels/create',[SalesChannelController::class, 'create'])->middleware('can:create,App\Models\SalesChannel');
    Route::get('/saleschannels/archive',[SalesChannelController::class, 'archive'])->middleware('can:restore,App\Models\SalesChannel')->middleware('can:forceDelete,App\Models\SalesChannel');
    Route::get('/saleschannels/{salesChannel}', [SalesChannelController::class, 'show'])->middleware('can:view,salesChannel');
    Route::post('/saleschannels', [SalesChannelController::class, 'store'])->middleware('can:create,App\Models\SalesChannel');
    Route::patch('/saleschannels/{salesChannel}', [SalesChannelController::class, 'update'])->middleware('can:update,salesChannel');
    Route::post('/saleschannels/bulkdelete', [SalesChannelController::class, 'bulkDelete']);//autherized in controller
    Route::post('/saleschannels/restore',[SalesChannelController::class, 'restore'])->middleware('can:restore,App\Models\SalesChannel');
    Route::post('/saleschannels/forcedelete',[SalesChannelController::class, 'forceDelete'])->middleware('can:foreceDelete,App\Models\SalesChannel');

    //InentoryLocations
    Route::get('/locations', [InventoryLocationController::class, 'index'])->middleware('can:viewAny,App\Models\InventoryLocation');
    Route::get('/locations/create', [InventoryLocationController::class, 'create'])->middleware('can:create,App\Models\InventoryLocation');
    Route::get('/locations/{location}', [InventoryLocationController::class, 'show'])->middleware('can:view,location');
    Route::post('/locations', [InventoryLocationController::class, 'store'])->middleware('can:create,App\Models\InventoryLocation');

    //Users (only admins can access these)
    Route::get('/users', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('can:create,App\Models\User');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('can:view,user');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:create,App\Models\User');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:delete,user');
});

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