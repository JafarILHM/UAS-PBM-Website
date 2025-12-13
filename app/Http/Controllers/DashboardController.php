<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KARTU STATISTIK UTAMA
        $totalItems = Item::count();
        $totalStock = Item::sum('stock');

        // Hitung transaksi bulan ini (Gunakan 'qty')
        $incomingThisMonth = IncomingItem::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty'); // <-- Perbaikan di sini

        $outgoingThisMonth = OutgoingItem::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty'); // <-- Perbaikan di sini

        // 2. PERINGATAN STOK MENIPIS
        $lowStockItems = Item::whereColumn('stock', '<=', 'stock_minimum')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // 3. TRANSAKSI TERBARU
        $recentIncoming = IncomingItem::with('item')
            ->latest()
            ->limit(5)
            ->get();

        $recentOutgoing = OutgoingItem::with('item')
            ->latest()
            ->limit(5)
            ->get();

        // 4. DATA UNTUK GRAFIK (Chart.js)
        $chartLabels = [];
        $chartIncoming = [];
        $chartOutgoing = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            $year = $date->format('Y');

            $chartLabels[] = $monthName;

            // Gunakan 'qty' untuk chart juga
            $chartIncoming[] = IncomingItem::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $year)
                ->sum('qty'); // <-- Perbaikan di sini

            $chartOutgoing[] = OutgoingItem::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $year)
                ->sum('qty'); // <-- Perbaikan di sini
        }

        return view('index', compact(
            'totalItems',
            'totalStock',
            'incomingThisMonth',
            'outgoingThisMonth',
            'lowStockItems',
            'recentIncoming',
            'recentOutgoing',
            'chartLabels',
            'chartIncoming',
            'chartOutgoing'
        ));
    }
}
