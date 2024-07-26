<?php

use App\Http\Controllers\WooCommerceWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PhotoController;

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

Route::prefix('/api/v1')->group(function() {
    Route::prefix('/products')->group(function() {
        Route::post('/archive', [ProductController::class, 'archiveProducts'])->name('products.archiveProducts');
        Route::post('/switchstatus', [ProductController::class, 'switchStatus'])->name('products.switchStatus');
        Route::delete('/delete', [ProductController::class, 'deleteProducts'])->name('products.deleteProducts');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/duplicate/{id}', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::delete('/delete/{id}', [ProductController::class, 'deleteById'])->name('products.delete');
        Route::put('/archive/{id}', [ProductController::class, 'archiveById'])->name('products.archive');
        Route::get('/export/{id}', [ProductController::class, 'exportById'])->name('products.exportByid');
        Route::post('/photos/store', [PhotoController::class, 'store'])->name('photos.store');
        Route::delete('/photos/{photo}', [PhotoController::class, 'delete'])->name('photos.delete');
    });
    
});