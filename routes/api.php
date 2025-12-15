<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\ItemController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\SupplierController;
use App\Http\Controllers\api\UnitController;
use App\Http\Controllers\api\IncomingItemController;
use App\Http\Controllers\api\OutgoingItemController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\TransactionController;

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
    Route::get('/transactions', [TransactionController::class, 'index']);

    // --- MASTER DATA: USER ---
    Route::apiResource('users', \App\Http\Controllers\api\UserController::class);

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
