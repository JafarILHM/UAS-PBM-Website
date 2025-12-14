<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UnitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (Login tidak butuh token)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (Harus login & punya token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth & User Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    // --- MASTER DATA
    // Items
    Route::get('/items', [ItemController::class, 'index']); // List Barang
    Route::post('/items', [ItemController::class, 'store']); // Tambah Barang
    Route::get('/items/{item}', [ItemController::class, 'show']); // Detail Barang
    Route::put('/items/{item}', [ItemController::class, 'update']); // Edit Barang
    Route::delete('/items/{item}', [ItemController::class, 'destroy']); // Hapus Barang

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Suppliers
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('units', UnitController::class);
});
