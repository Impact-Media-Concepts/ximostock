<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryLocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SalesChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WooCommerceWebhookController;
use Illuminate\Support\Facades\Log;
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
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//product
Route::middleware('auth')->group(function () {

    // Products prefixed and grouped
    Route::prefix('/products')->middleware('auth')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->middleware('can:viewAny,App\Models\Product');
        Route::get('/create', [ProductController::class, 'create'])->middleware('can:create,App\Models\Product');
        Route::get('/export', [ProductController::class, 'export']);//export
        Route::get('/archive', [ProductController::class, 'archive'])->middleware('can:forceDelete,App\Models\Product')->middleware('can:restore,App\Models\Product');
        Route::get('/{product}', [ProductController::class, 'show'])->middleware('can:view,product');
        Route::post('/', [ProductController::class, 'store']);//autherized in controller
        Route::patch('/{product}', [ProductController::class, 'update']);//autherized in controller
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('can:delete,product');
        Route::post('/bulkdelete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');//autherized in controller
        Route::post('/bulkdiscount', [ProductController::class, 'bulkDiscount'])->name('products.bulkDiscount');//autherized in controller
        Route::post('/bulkdiscountforce', [ProductController::class, 'bulkDiscountForce']);//autherized in controller
        Route::post('/bulklinksaleschannel', [ProductController::class, 'bulkLinkSalesChannel'])->name('products.bulkLinkSalesChannel');//autherized in controller
        Route::post('/bulkunlinksaleschannel', [ProductController::class, 'bulkUnlinkSalesChannel'])->name('products.bulkUnlinkSalesChannel');//autherized in controller
        Route::post('/bulkenablebackorders', [ProductController::class, 'bulkEnableBackorders'])->name('products.bulkEnableBackorders');//autherized in controller
        Route::post('/bulkdisablebackorders', [ProductController::class, 'bulkDisableBackorders'])->name('products.bulkDisableBackorders');//autherized in controller
        Route::post('/bulkenablecommunicateStock', [ProductController::class, 'bulkEnableCommunicateStock'])->name('products.bulkEnableCommunicateStock');//autherized in controller
        Route::post('/bulkdisablecommunicateStock', [ProductController::class, 'bulkDisableCommunicateStock'])->name('products.bulkDisableCommunicateStock');//autherized in controller
        Route::post('/restore', [ProductController::class, 'restore'])->middleware('can:restore,App\Models\Product');
        Route::post('/forcedelete', [ProductController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\Product');

        //variant product
        Route::get('/variant/create', [ProductVariationController::class, 'create'])->middleware('can:create,App\Models\Product');
        Route::post('/variant', [ProductVariationController::class, 'store']);//autherized in controller
    });

    // Categories prefixed and grouped
    Route::prefix('/categories')->middleware('auth')->group(function() {
        Route::get('', [CategoryController::class, 'index'])->middleware('can:viewAny,App\Models\Category');
        Route::get('/create', [CategoryController::class, 'create'])->middleware('can:create,App\Models\Category');
        Route::get('/archive', [CategoryController::class, 'archive'])->middleware('can:restore,App\Models\categories')->middleware('can:forceDelete,App\Models\categories');
        Route::get('/{category}', [CategoryController::class, 'show'])->middleware('can:view,category');
        Route::post('', [CategoryController::class, 'store']);//autherized in controller
        Route::patch('/{category}', [CategoryController::class, 'update']);//autherized in controller
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware('can:delete,category');
        Route::post('/restore', [CategoryController::class, 'restore'])->middleware('can:restore,App\Models\categories');
        Route::post('/forcedelete', [CategoryController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\categories');
    });
    
    // Properties prefixed and grouped
    Route::prefix('/properties')->middleware('auth')->group(function() {
        //properties
        Route::get('', [PropertyController::class, 'index'])->middleware('can:viewAny,App\Models\Property');
        Route::get('/create', [PropertyController::class, 'create'])->middleware('can:create,App\Models\Property');
        Route::get('/archive', [PropertyController::class, 'archive'])->middleware('can:restore,App\Models\Property')->middleware('can:forceDelete,App\Models\Property');
        Route::get('/{property}', [PropertyController::class, 'show'])->middleware('can:view,property');
        Route::post('', [PropertyController::class, 'store'])->middleware('can:create,App\Models\Property');
        Route::patch('/{property}', [PropertyController::class, 'update'])->middleware('can:update,property');
        Route::post('/bulkdelete', [PropertyController::class, 'bulkDelete']);//autherized in controller
        Route::post('/restore', [PropertyController::class, 'restore'])->middleware('can:restore,App\Models\Property');
        Route::post('/forcedelete', [PropertyController::class, 'forceDelete'])->middleware('can:foreceDelete,App\Models\Property');
    });

    Route::prefix('/saleschannels')->middleware('auth')->group(function() {
        Route::get('', [SalesChannelController::class, 'index'])->middleware('can:viewAny,App\Models\SalesChannel');
        Route::get('/create', [SalesChannelController::class, 'create'])->middleware('can:create,App\Models\SalesChannel');
        Route::get('/archive', [SalesChannelController::class, 'archive'])->middleware('can:restore,App\Models\SalesChannel')->middleware('can:forceDelete,App\Models\SalesChannel');
        Route::get('/{salesChannel}', [SalesChannelController::class, 'show'])->middleware('can:view,salesChannel');
        Route::post('', [SalesChannelController::class, 'store'])->middleware('can:create,App\Models\SalesChannel');
        Route::patch('/{salesChannel}', [SalesChannelController::class, 'update'])->middleware('can:update,salesChannel');
        Route::post('/bulkdelete', [SalesChannelController::class, 'bulkDelete']);//autherized in controller
        Route::post('/restore', [SalesChannelController::class, 'restore'])->middleware('can:restore,App\Models\SalesChannel');
        Route::post('/forcedelete', [SalesChannelController::class, 'forceDelete'])->middleware('can:foreceDelete,App\Models\SalesChannel');
    });
    

    Route::prefix('/locations')->middleware('auth')->group(function() {
        Route::get('', [InventoryLocationController::class, 'index'])->middleware('can:viewAny,App\Models\InventoryLocation');
        Route::get('/create', [InventoryLocationController::class, 'create'])->middleware('can:create,App\Models\InventoryLocation');
        Route::get('/{location}', [InventoryLocationController::class, 'show'])->middleware('can:view,location');
        Route::post('', [InventoryLocationController::class, 'store'])->middleware('can:create,App\Models\InventoryLocation');
    });
    
    Route::prefix('/users')->middleware('auth')->group(function() {
        Route::get('', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User');
        Route::get('/create', [UserController::class, 'create'])->middleware('can:create,App\Models\User');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('can:view,user');
        Route::post('', [UserController::class, 'store'])->middleware('can:create,App\Models\User');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('can:delete,user');
    });
    
    Route::get('/activity-log', [ActivityLogController::class, 'index']);
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

Route::post('/webhooks/woocommerce', [WooCommerceWebhookController::class , 'handle']);

require __DIR__ . '/auth.php';