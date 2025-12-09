<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\Item;
use App\Models\OutgoingItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total items in warehouse
        $totalItems = Item::sum('stock');

        // Top items by quantity
        $topItems = Item::orderByDesc('stock')
            ->take(5)
            ->get();

        // Items with deadline today
        $itemsWithTodayDeadline = IncomingItem::whereDate('deadline', Carbon::today())->get();

        // Incoming and outgoing items today
        $incomingToday = IncomingItem::whereDate('date_in', Carbon::today())->sum('qty');
        $outgoingToday = OutgoingItem::whereDate('date_out', Carbon::today())->sum('qty');

        return response()->json([
            'total_items_in_warehouse' => $totalItems,
            'top_items_by_quantity' => $topItems,
            'items_with_today_deadline' => $itemsWithTodayDeadline,
            'incoming_items_today' => $incomingToday,
            'outgoing_items_today' => $outgoingToday,
        ]);
    }
}