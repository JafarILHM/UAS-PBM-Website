<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 
use App\Models\IncomingItem;
use App\Models\Item;

class IncomingItemController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'qty' => 'required|integer|min:1',
            'date_in' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                IncomingItem::create([
                    'item_id' => $request->item_id,
                    'supplier_id' => $request->supplier_id,
                    'operator_id' => auth()->id(),
                    'qty' => $request->qty,
                    'date_in' => $request->date_in,
                ]);

                $item = Item::findOrFail($request->item_id);
                $item->stock += $request->qty;
                $item->save();
            });

            return response()->json(['success' => true, 'message' => 'Stok masuk berhasil disimpan']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
}
