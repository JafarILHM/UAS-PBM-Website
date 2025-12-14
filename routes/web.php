<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controller API
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\IncomingItemController;
use App\Http\Controllers\Api\OutgoingItemController;
use App\Http\Controllers\Api\ProfileController;

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

// Route Public
Route::post('/login', [AuthController::class, 'login']);

// Route Private (Perlu Token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/profile/update', [ProfileController::class, 'update']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // Master Data
    Route::apiResource('items', ItemController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('units', UnitController::class);

    // Transaksi
    Route::post('/incoming', [IncomingItemController::class, 'store']);
    Route::post('/outgoing', [OutgoingItemController::class, 'store']);

    // Rute scan SKU
    Route::get('/items/scan/{sku}', [ItemController::class, 'findBySku']);

    Route::apiResource('items', ItemController::class);

    // Route Template AdminKit (Opsional: Nanti bisa dihapus jika fitur sudah jadi)
    // Route::get('/profile', function () { return view('pages-profile'); });
    Route::get('/blank', function () { return view('pages-blank'); });
    Route::get('/ui-buttons', function () { return view('ui-buttons'); });
    Route::get('/ui-forms', function () { return view('ui-forms'); });
    Route::get('/ui-cards', function () { return view('ui-cards'); });
    Route::get('/ui-typography', function () { return view('ui-typography'); });
    Route::get('/icons-feather', function () { return view('icons-feather'); });
    Route::get('/charts-chartjs', function () { return view('charts-chartjs'); });
    Route::get('/maps-google', function () { return view('maps-google'); });
});
