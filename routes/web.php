<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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

// --- GROUP GUEST: Hanya bisa diakses jika BELUM login ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

// --- GROUP AUTH: Hanya bisa diakses jika SUDAH login ---
Route::middleware('auth')->group(function () {
    // Dashboard Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Route untuk Halaman Profil (Self Service)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Master Data
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('units', \App\Http\Controllers\UnitController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('items', \App\Http\Controllers\ItemController::class)->except(['show']);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);


    // Transaksi Barang Masuk dan keluar
    Route::resource('incoming', \App\Http\Controllers\IncomingItemController::class);
    Route::resource('outgoing', \App\Http\Controllers\OutgoingItemController::class);

    // Route Export Excel (Taruh SEBELUM resource items)
    Route::get('items/export', [\App\Http\Controllers\ItemController::class, 'export'])->name('items.export');
    Route::resource('items', \App\Http\Controllers\ItemController::class);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
