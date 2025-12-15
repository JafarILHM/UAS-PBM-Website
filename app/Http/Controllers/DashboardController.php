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
        // KARTU STATISTIK UTAMA
        $totalItems = Item::count();
        $totalStock = Item::sum('stock');

        // Hitung transaksi bulan ini
        $incomingThisMonth = IncomingItem::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty');

        $outgoingThisMonth = OutgoingItem::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty'); 

        // PERINGATAN STOK MENIPIS
        $lowStockItems = Item::whereColumn('stock', '<=', 'stock_minimum')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // TRANSAKSI TERBARU
        $recentIncoming = IncomingItem::with('item')
            ->latest()
            ->limit(5)
            ->get();

        $recentOutgoing = OutgoingItem::with('item')
            ->latest()
            ->limit(5)
            ->get();

        // DATA UNTUK GRAFIK (Chart.js)
        $chartLabels = [];
        $chartIncoming = [];
        $chartOutgoing = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            $year = $date->format('Y');

            $chartLabels[] = $monthName;

            $chartIncoming[] = IncomingItem::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $year)
                ->sum('qty');

            $chartOutgoing[] = OutgoingItem::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $year)
                ->sum('qty');
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
