<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OutgoingItem;
use App\Models\Item;

class OutgoingItemController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'date_out' => 'required|date',
            'purpose' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Cek Stok Dulu
        $item = Item::findOrFail($request->item_id);
        if ($item->stock < $request->qty) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak cukup! Sisa: {$item->stock}"
            ], 400);
        }

        try {
            DB::transaction(function () use ($request, $item) {
                // Simpan Catatan Keluar
                OutgoingItem::create([
                    'item_id' => $request->item_id,
                    'operator_id' => auth()->id(),
                    'qty' => $request->qty,
                    'purpose' => $request->purpose,
                    'date_out' => $request->date_out,
                ]);

                // Kurangi Stok Barang
                $item->stock -= $request->qty;
                $item->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Barang keluar berhasil dicatat!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
