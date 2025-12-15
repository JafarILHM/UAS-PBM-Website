<?php

namespace App\Http\Controllers\api; 

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Item::whereColumn('stock', '<=', 'stock_minimum')->count();
        $recentIncoming = IncomingItem::with('item')->latest()->take(3)->get();
        $recentOutgoing = OutgoingItem::with('item')->latest()->take(3)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_items' => $totalItems,
                'total_suppliers' => $totalSuppliers,
                'low_stock_count' => $lowStockCount,
                'recent_incoming' => $recentIncoming,
                'recent_outgoing' => $recentOutgoing,
            ]
        ]);
    }
}
