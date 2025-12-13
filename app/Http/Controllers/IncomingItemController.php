<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\ItemBatch; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingItemController extends Controller
{
    // Tampilkan Riwayat Barang Masuk
    public function index()
    {
        $incomingItems = IncomingItem::with(['item', 'supplier', 'user'])->latest()->get();
        return view('incoming.index', compact('incomingItems'));
    }

    // Form Input Barang Masuk
    public function create()
    {
        $suppliers = Supplier::all();
        // Kita kirim data items untuk pencarian via Select2 atau Datalist
        $items = Item::select('id', 'name', 'sku', 'unit')->get();
        return view('incoming.create', compact('suppliers', 'items'));
    }

    // Proses Simpan
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'batch_no' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);

        // Gunakan DB Transaction agar data konsisten (semua sukses atau semua gagal)
        DB::transaction(function () use ($request) {
            // 1. Simpan Riwayat Barang Masuk
            $incoming = IncomingItem::create([
                'item_id' => $request->item_id,
                'user_id' => auth()->id(), // Siapa yang input
                'supplier_id' => $request->supplier_id,
                'quantity' => $request->quantity,
                'batch_no' => $request->batch_no,
                'expiry_date' => $request->expiry_date,
                'notes' => $request->notes,
                'transaction_date' => now(),
            ]);

            // 2. Tambah Stok di Master Barang
            $item = Item::findOrFail($request->item_id);
            $item->stock += $request->quantity;
            $item->save();

            // 3. Simpan/Update Batch (Jika ada batch number)
            if ($request->batch_no) {
                // Cek apakah batch ini sudah ada untuk barang tersebut?
                $batch = ItemBatch::where('item_id', $request->item_id)
                                  ->where('batch_no', $request->batch_no)
                                  ->first();

                if ($batch) {
                    // Jika ada, tambah stok batch-nya
                    $batch->stock += $request->quantity;
                    $batch->save();
                } else {
                    // Jika belum, buat batch baru
                    ItemBatch::create([
                        'item_id' => $request->item_id,
                        'batch_no' => $request->batch_no,
                        'expiry_date' => $request->expiry_date,
                        'stock' => $request->quantity,
                    ]);
                }
            }
        });

        return redirect()->route('incoming.index')->with('success', 'Stok berhasil ditambahkan!');
    }
}
