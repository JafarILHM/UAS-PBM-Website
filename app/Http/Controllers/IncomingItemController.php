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
    public function index()
    {
        $incomingItems = IncomingItem::with(['item', 'supplier', 'user'])->latest()->get();
        return view('incoming.index', compact('incomingItems'));
    }

    public function create()
    {
        $suppliers = Supplier::all();

        //  Tambahkan with('unit') dan select 'unit_id'
        $items = Item::with('unit')
            ->select('id', 'name', 'sku', 'unit_id')
            ->get();

        return view('incoming.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        IncomingItem::create([
            'item_id' => $request->item_id,
            'operator_id' => auth()->id(),
            'supplier_id' => $request->supplier_id,
            'qty' => $request->quantity,
            'batch' => $request->batch_no,
            'deadline' => $request->expiry_date,
            'date_in' => now(),
        ]);

        DB::transaction(function () use ($request) {
            IncomingItem::create([
                'item_id' => $request->item_id,
                'user_id' => auth()->id(),
                'supplier_id' => $request->supplier_id,
                'qty' => $request->quantity, // Pastikan sesuai nama kolom di DB (qty)
                'batch_no' => $request->batch_no,
                'expiry_date' => $request->expiry_date,
                'notes' => $request->notes,
                'transaction_date' => now(),
            ]);

            $item = Item::findOrFail($request->item_id);
            $item->stock += $request->quantity;
            $item->save();

            if ($request->batch_no) {
                $batch = ItemBatch::where('item_id', $request->item_id)
                                  ->where('batch_no', $request->batch_no)
                                  ->first();

                if ($batch) {
                    $batch->stock += $request->quantity;
                    $batch->save();
                } else {
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
