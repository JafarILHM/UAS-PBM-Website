<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemBatchController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockReservationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Items
    Route::apiResource('items', ItemController::class);
    Route::post('/items/incoming', [ItemController::class, 'incoming']);
    Route::post('/items/outgoing', [ItemController::class, 'outgoing']);
    Route::get('/items/export', [ItemController::class, 'export']);
    Route::get('/items/{item}/barcode', [ItemController::class, 'showBarcode']);
    Route::get('/items/scan/{code}', [ItemController::class, 'scan']);

    // Suppliers
    Route::apiResource('suppliers', SupplierController::class);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Units
    Route::apiResource('units', UnitController::class);
    Route::get('/unit-conversions', [UnitController::class, 'indexConversions']);
    Route::post('/unit-conversions', [UnitController::class, 'storeConversion']);
    Route::get('/unit-conversions/{unitConversion}', [UnitController::class, 'showConversion']);
    Route::put('/unit-conversions/{unitConversion}', [UnitController::class, 'updateConversion']);
    Route::delete('/unit-conversions/{unitConversion}', [UnitController::class, 'destroyConversion']);

    // Item Batches
    Route::apiResource('item-batches', ItemBatchController::class);

    // Stock Reservations
    Route::apiResource('stock-reservations', StockReservationController::class);
    Route::post('/stock-reservations/{stockReservation}/fulfill', [StockReservationController::class, 'fulfill']);

    // Audit Logs (read-only)
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show']);
});
