<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data barang masuk dengan relasi item
            $incoming = IncomingItem::with('item')->latest()->get()->map(function ($item) {
                $item->type = 'in';
                return $item;
            });

            // Ambil semua data barang keluar dengan relasi item
            $outgoing = OutgoingItem::with('item')->latest()->get()->map(function ($item) {
                $item->type = 'out';
                return $item;
            });

            // Gabungkan kedua koleksi
            $transactions = $incoming->concat($outgoing);

            // Urutkan berdasarkan tanggal pembuatan (created_at) dari yang terbaru
            $sortedTransactions = $transactions->sortByDesc('created_at')->values();

            return response()->json([
                'success' => true,
                'data' => $sortedTransactions,
                'message' => 'Transactions fetched successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
