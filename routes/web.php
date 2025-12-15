<?php

use Illuminate\Support\Facades\Route;

// --- 1. IMPORT CONTROLLER WEB (Pastikan BUKAN folder Api) ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController; // Pastikan ini ItemController versi Web
use App\Http\Controllers\IncomingItemController;
use App\Http\Controllers\OutgoingItemController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes (Website Admin Panel)
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login-action', [AuthController::class, 'login'])->name('login.action');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- PROTECTED ROUTES (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- MASTER DATA ---
    // Data Barang (Items)
    Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
    Route::resource('items', ItemController::class);

    // Master Data Lainnya
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('units', UnitController::class);

    // --- TRANSAKSI ---
    // Barang Masuk
    Route::get('/incoming', [IncomingItemController::class, 'index'])->name('incoming.index');
    Route::get('/incoming/create', [IncomingItemController::class, 'create'])->name('incoming.create');
    Route::post('/incoming', [IncomingItemController::class, 'store'])->name('incoming.store');

    // Barang Keluar
    Route::get('/outgoing', [OutgoingItemController::class, 'index'])->name('outgoing.index');
    Route::get('/outgoing/create', [OutgoingItemController::class, 'create'])->name('outgoing.create');
    Route::post('/outgoing', [OutgoingItemController::class, 'store'])->name('outgoing.store');

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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
