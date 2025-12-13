<?php

namespace App\Http\Controllers;

use App\Models\OutgoingItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingItemController extends Controller
{
    public function index()
    {
        $outgoingItems = OutgoingItem::with(['item', 'user'])->latest()->get();
        return view('outgoing.index', compact('outgoingItems'));
    }

    public function create()
    {
        // PERBAIKAN: Tambahkan with('unit') dan select 'unit_id'
        $items = Item::with('unit')
            ->where('stock', '>', 0)
            ->select('id', 'name', 'sku', 'stock', 'unit_id')
            ->get();

        return view('outgoing.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->stock < $request->quantity) {
            return back()->withErrors(['quantity' => "Stok tidak cukup! Sisa stok hanya: {$item->stock}"])->withInput();
        }

        DB::transaction(function () use ($request, $item) {
            OutgoingItem::create([
                'item_id' => $request->item_id,
                'user_id' => auth()->id(),
                'qty' => $request->quantity, // Pastikan sesuai nama kolom di DB (qty)
                'notes' => $request->notes,
                'date_out' => now(), // Sesuai kolom di migrasi
            ]);

            $item->stock -= $request->quantity;
            $item->save();
        });

        return redirect()->route('outgoing.index')->with('success', 'Barang berhasil dikeluarkan!');
    }
}
