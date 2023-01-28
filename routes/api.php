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
    Route::get('/', [PackageController::class, 'index']);
    Route::get('/{id}', [PackageController::class, 'show']);
    Route::post('/', [PackageController::class, 'storeTransaction']);
    Route::post('/koli/{connote_id}', [PackageController::class, 'storeKoli']);
    Route::put('/{id}', [PackageController::class, 'updateTransaction']);
    Route::put('/koli/{id}', [PackageController::class, 'updateKoli']);
    Route::patch('/{id}', [PackageController::class, 'updatePayment']);
    Route::delete('/{id}', [PackageController::class, 'destroy']);
});
