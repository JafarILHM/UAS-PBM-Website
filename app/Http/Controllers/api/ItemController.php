<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Ambil Semua Barang
    public function index()
    {
        $items = Item::with(['category', 'unit'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    // Tambah Barang dari HP
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sku' => 'required|unique:items',
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = Item::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil disimpan',
            'data' => $item
        ]);
    }

    // Update Barang
    public function update(Request $request, Item $item)
    {
        $item->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Barang update',
            'data' => $item
        ]);
    }

    // Hapus Barang
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json([
            'success' => true,
            'message' => 'Barang dihapus'
        ]);
    }
}
