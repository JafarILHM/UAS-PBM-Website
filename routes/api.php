<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\IncomingItemController;
use App\Http\Controllers\Api\OutgoingItemController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

// PUBLIC ROUTES (Bisa diakses tanpa Login / Tanpa Token)
Route::post('/login', [AuthController::class, 'login']);

// PROTECTED ROUTES (Harus Login & Punya Token)
Route::middleware('auth:sanctum')->group(function () {

    // --- AUTHENTICATION & USER ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/profile/update', [ProfileController::class, 'update']);

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // --- MASTER DATA: BARANG (ITEMS) ---
    Route::get('/items/scan/{sku}', [ItemController::class, 'findBySku']);
    Route::apiResource('items', ItemController::class);

    // --- MASTER DATA LAINNYA ---
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('units', UnitController::class);

    // --- TRANSAKSI (Update Stok) ---
    Route::post('/incoming', [IncomingItemController::class, 'store']);
    Route::post('/outgoing', [OutgoingItemController::class, 'store']);

});
