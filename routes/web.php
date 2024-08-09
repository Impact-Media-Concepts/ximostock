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
use App\Http\Controllers\WorkSpaceController;
use App\Http\Controllers\WooCommerceWebhookController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\auth\PasswordController;
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
        Route::get('/', [ProductController::class, 'index'])->middleware('can:viewAny,App\Models\Product')->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->middleware('can:create,App\Models\Product')->name('products.create');
        Route::get('/export', [ProductController::class, 'export'])->name('products.export');
        Route::get('/{id}/variation/add', [ProductController::class, 'addVariation'])->name('products.addVariation');
        Route::get('/{product}', [ProductController::class, 'show'])->middleware('can:view,product')->name('products.show');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('can:delete,product');
        Route::post('/bulkdelete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
        Route::post('/bulkdiscount', [ProductController::class, 'bulkDiscount'])->name('products.bulkDiscount');
        Route::post('/bulkdiscountforce', [ProductController::class, 'bulkDiscountForce'])->name('products.bulkDiscountForce');
        Route::post('/bulklinksaleschannel', [ProductController::class, 'bulkLinkSalesChannel'])->name('products.bulkLinkSalesChannel');
        Route::post('/bulkunlinksaleschannel', [ProductController::class, 'bulkUnlinkSalesChannel'])->name('products.bulkUnlinkSalesChannel');
        Route::post('/bulkenablebackorders', [ProductController::class, 'bulkEnableBackorders'])->name('products.bulkEnableBackorders');
        Route::post('/bulkdisablebackorders', [ProductController::class, 'bulkDisableBackorders'])->name('products.bulkDisableBackorders');
        Route::post('/bulkenablecommunicateStock', [ProductController::class, 'bulkEnableCommunicateStock'])->name('products.bulkEnableCommunicateStock');
        Route::post('/bulkdisablecommunicateStock', [ProductController::class, 'bulkDisableCommunicateStock'])->name('products.bulkDisableCommunicateStock');
        Route::post('/restore', [ProductController::class, 'restore'])->middleware('can:restore,App\Models\Product')->name('products.restore');
        Route::post('/forcedelete', [ProductController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\Product')->name('products.forceDelete');

        //variant product
        Route::get('/variant/create', [ProductVariationController::class, 'create'])->middleware('can:create,App\Models\Product')->name('products.variant.create');
        Route::post('/variant', [ProductVariationController::class, 'store'])->name('products.variant.store');
    });

    // Categories prefixed and grouped
    Route::prefix('/categories')->middleware('auth')->group(function() {
        Route::get('', [CategoryController::class, 'index'])->middleware('can:viewAny,App\Models\Category')->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->middleware('can:create,App\Models\Category')->name('categories.create');
        Route::get('/archive', [CategoryController::class, 'archive'])->middleware('can:restore,App\Models\categories')->middleware('can:forceDelete,App\Models\categories')->name('categories.archive');
        Route::get('/{category}', [CategoryController::class, 'show'])->middleware('can:view,category')->name('categories.show');
        Route::post('', [CategoryController::class, 'store'])->name('categories.store');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware('can:delete,category')->name('categories.destroy');
        Route::post('/restore', [CategoryController::class, 'restore'])->middleware('can:restore,App\Models\categories')->name('categories.restore');
        Route::post('/forcedelete', [CategoryController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\categories')->name('categories.forceDelete');
    });
    
    // Properties prefixed and grouped
    Route::prefix('/properties')->middleware('auth')->group(function() {
        Route::get('', [PropertyController::class, 'index'])->middleware('can:viewAny,App\Models\Property')->name('properties.index');
        Route::get('/create', [PropertyController::class, 'create'])->middleware('can:\Property')->name('properties.create');
        Route::get('/archive', [PropertyController::class, 'archive'])->middleware('can:create,App\Models:restore,App\Models\Property')->middleware('can:forceDelete,App\Models\Property')->name('properties.archive');
        Route::get('/{property}', [PropertyController::class, 'show'])->middleware('can:view,property')->name('properties.show');
        Route::post('', [PropertyController::class, 'store'])->middleware('can:create,App\Models\Property')->name('properties.store');
        Route::patch('/{property}', [PropertyController::class, 'update'])->middleware('can:update,property')->name('properties.update');
        Route::post('/bulkdelete', [PropertyController::class, 'bulkDelete'])->name('properties.bulkDelete');
        Route::post('/restore', [PropertyController::class, 'restore'])->middleware('can:restore,App\Models\Property')->name('properties.restore');
        Route::post('/forcedelete', [PropertyController::class, 'forceDelete'])->middleware('can:foreceDelete,App\Models\Property')->name('properties.forceDelete');
    });

    Route::prefix('/saleschannels')->middleware('auth')->group(function() {
        Route::get('', [SalesChannelController::class, 'index'])->middleware('can:viewAny,App\Models\SalesChannel')->name('saleschannels.index');
        Route::get('/{salesChannel}', [SalesChannelController::class, 'show'])->middleware('can:view,salesChannel')->name('saleschannels.show');
        Route::post('', [SalesChannelController::class, 'store'])->middleware('can:create,App\Models\SalesChannel')->name('saleschannels.store');
        Route::patch('/{salesChannel}', [SalesChannelController::class, 'update'])->middleware('can:update,salesChannel')->name('saleschannels.update');
        Route::post('/restore', [SalesChannelController::class, 'restore'])->middleware('can:restore,App\Models\SalesChannel')->name('saleschannels.restore');
        Route::post('/forcedelete', [SalesChannelController::class, 'forceDelete'])->middleware('can:foreceDelete,App\Models\SalesChannel')->name('saleschannels.forceDelete');
    });
    

    Route::prefix('/locations')->middleware('auth')->group(function() {
        Route::get('/', [InventoryLocationController::class, 'index'])->middleware('can:viewAny,App\Models\InventoryLocation')->name('locations.index');
        Route::get('/create', [InventoryLocationController::class, 'create'])->middleware('can:create,App\Models\InventoryLocation')->name('locations.create');
        Route::get('/{location}', [InventoryLocationController::class, 'show'])->middleware('can:view,location')->name('locations.show');
        Route::post('', [InventoryLocationController::class, 'store'])->middleware('can:create,App\Models\InventoryLocation')->name('locations.store');
    });
    
    Route::prefix('/users')->middleware('auth')->group(function() {
        Route::get('/', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User')->name('users.index');
        Route::get('/theme', [UserController::class, 'theme'])->name('users.theme');
        Route::get('/create', [UserController::class, 'create'])->middleware('can:viewAny,App\Models\User')->name('users.create');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('can:viewAny,App\Models\User')->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('can:viewAny,App\Models\User')->name('users.edit');
        Route::post('', [UserController::class, 'store'])->middleware('can:viewAny,App\Models\User')->name('users.store');
        Route::patch('/{user}', [UserController::class, 'update'])->middleware('can:viewAny,App\Models\User')->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('can:viewAny,App\Models\User')->name('users.destroy');
    });

    Route::prefix('/workspaces')->middleware('auth')->group(function() {
        Route::get('/', [WorkSpaceController::class, 'index'])->name('workspaces.index');
        Route::get('/create', [WorkSpaceController::class, 'create'])->name('workspaces.create');
        Route::post('/', [WorkSpaceController::class, 'store'])->name('workspaces.store');
        Route::get('/{workspace}', [WorkSpaceController::class, 'show'])->name('workspaces.show');
        Route::get('/{workspace}/edit', [WorkSpaceController::class, 'edit'])->name('workspaces.edit');
        Route::patch('/{workspace}', [WorkSpaceController::class, 'update'])->name('workspaces.update');
        Route::delete('/{workspace}', [WorkSpaceController::class, 'destroy'])->name('workspaces.destroy');
        Route::post('/switch', [WorkSpaceController::class, 'switch'])->name('workspaces.switch');
    });

    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/notification', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
});

//authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/reset-password', [PasswordController::class, 'view'])->middleware(['auth', 'verified'])->name('reset-password');
Route::post('/update-password', [PasswordController::class, 'update'])->middleware(['auth', 'verified'])->name('update-password');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/webhooks/woocommerce', [WooCommerceWebhookController::class , 'handle']);

require __DIR__ . '/auth.php';