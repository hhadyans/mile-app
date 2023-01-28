<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/package')->group(function() {
    Route::get('/', [PackageController::class, 'index'])->name('api.get.transaction');
    Route::get('/{id}', [PackageController::class, 'show'])->name('api.detail.transaction');
    Route::post('/', [PackageController::class, 'storeTransaction'])->name('api.post.transaction');
    Route::post('/koli/{id}', [PackageController::class, 'storeKoli'])->name('api.post.koli');
    Route::put('/{id}', [PackageController::class, 'updateTransaction'])->name('api.put.transaction');
    Route::put('/koli/{id}', [PackageController::class, 'updateKoli'])->name('api.put.koli');
    Route::patch('/{id}', [PackageController::class, 'updatePayment'])->name('api.patch.payment');
    Route::delete('/{id}', [PackageController::class, 'destroy'])->name('api.delete.transaction');
});
