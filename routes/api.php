<?php

use App\Http\Controllers\WooCommerceWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SalesChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\InventoryLocationController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/api/v1')->middleware(['web'])->group(function() {
    Route::prefix('/products')->group(function() {
        Route::post('/archive', [ProductController::class, 'archiveProducts'])->name('products.archiveProducts');
        Route::post('/switchstatus', [ProductController::class, 'switchStatus'])->name('products.switchStatus');
        Route::delete('/delete', [ProductController::class, 'deleteProducts'])->name('products.deleteProducts');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/duplicate/{id}', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::delete('/delete/{id}', [ProductController::class, 'deleteById'])->name('products.delete');
        Route::put('/archive/{id}', [ProductController::class, 'archiveById'])->name('products.archive');
        Route::get('/export/{id}', [ProductController::class, 'exportById'])->name('products.exportByid');
    });

    Route::prefix('/products')->group(function() {
        Route::post('/photos/store', [PhotoController::class, 'store'])->name('photos.store');
        Route::delete('/photos/{photo}', [PhotoController::class, 'delete'])->name('photos.delete');
    });

    Route::prefix('/saleschannels')->group(function() {
        Route::delete('/delete/{saleschannel}', [SalesChannelController::class, 'deleteById'])->name('saleschannels.deleteBuId');
        Route::post('', [SalesChannelController::class, 'store'])->name('saleschannels.store');
        Route::put('/update/{saleschannel}', [SalesChannelController::class, 'updateById'])->name('saleschannels.updateById');
        Route::post('/bulkdelete', [SalesChannelController::class, 'bulkDelete'])->name('saleschannels.bulkDelete');
    });


    Route::prefix('/user')->group(function() {
        Route::patch('/theme/update/{id}', [UserController::class, 'updateThemeById'])->name('user.updateThemeById');
    });

    Route::prefix('/categories')->group(function() {
        Route::delete('/deleteCategories', [CategoryController::class, 'deleteCategories'])->name('categories.deleteCategories');
        Route::patch('/updateCategories', [CategoryController::class, 'updateCategories'])->name('categories.updateCategories');
    });

    Route::prefix('/archive')->group(function(){
        Route::post('/restore', [ArchiveController::class, 'restore'])->name('archive.restore');
        Route::post('/forcedelete', [ArchiveController::class, 'forceDelete'])->name('archive.forcedelete');
        Route::post('/bulkrestore', [ArchiveController::class, 'bulkRestore'])->name('archive.bulkrestore');
        Route::post('/bulkforcedelete', [ArchiveController::class, 'bulkForceDelete'])->name('archive.bulkforcedelete');
    });

    Route::prefix('/locations')->group(function(){
        Route::delete('/delete/{inventorylocation}', [InventoryLocationController::class, 'deleteById'])->name('locations.deletebyid');
        Route::post('/bulkdelete', [InventoryLocationController::class, 'bulkDelete'])->name('locations.bulkDelete');
        Route::put('/update',[InventoryLocationController::class, 'update'])->name('locations.update');
        Route::post('', [InventoryLocationController::class, 'store'])->middleware('can:create,App\Models\InventoryLocation')->name('locations.store');
    });
    Route::prefix('/suppliers')->group(function(){
        Route::delete('/delete/{supplier}', [SupplierController::class, 'deleteById'])->name('suppliers.deleteById');
        Route::delete('/bulkdelete', [SupplierController::class, 'bulkDelete'])->name('suppliers.bulkDelete');
        Route::put('/update',[SupplierController::class, 'update'])->name('suppliers.update');
        Route::post('', [SupplierController::class, 'store'])->name('suppliers.store');
    });

});
