<?php

namespace App\Http\Controllers;

use App\Models\OutgoingItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingItemController extends Controller
{
    // Tampilkan Riwayat Barang Keluar
    public function index()
    {
        $outgoingItems = OutgoingItem::with(['item', 'user'])->latest()->get();
        return view('outgoing.index', compact('outgoingItems'));
    }

    // Form Input Barang Keluar
    public function create()
    {
        // Ambil barang yang stoknya > 0 saja
        $items = Item::where('stock', '>', 0)
                     ->select('id', 'name', 'sku', 'unit', 'stock')
                     ->get();

        return view('outgoing.create', compact('items'));
    }

    // Proses Simpan (Kurangi Stok)
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Validasi: Cek apakah stok cukup?
        if ($item->stock < $request->quantity) {
            return back()->withErrors(['quantity' => "Stok tidak cukup! Sisa stok hanya: {$item->stock} {$item->unit}"])->withInput();
        }

        // Gunakan DB Transaction
        DB::transaction(function () use ($request, $item) {
            // 1. Simpan Riwayat Keluar
            OutgoingItem::create([
                'item_id' => $request->item_id,
                'user_id' => auth()->id(),
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'transaction_date' => now(),
            ]);

            // 2. Kurangi Stok Master
            $item->stock -= $request->quantity;
            $item->save();

            // Catatan: Jika menggunakan batch (FIFO/LIFO), logika pengurangan batch bisa ditambahkan di sini.
            // Untuk versi dasar, kita kurangi stok total saja dulu.
        });

        return redirect()->route('outgoing.index')->with('success', 'Barang berhasil dikeluarkan!');
    }
}
