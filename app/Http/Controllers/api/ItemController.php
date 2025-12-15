<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'unit'])->latest()->get();
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
        return response()->json(['success' => true, 'message' => 'Barang berhasil disimpan', 'data' => $item]);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'unit'])->find($id);
        if (!$item) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function findBySku($sku)
    {
        $item = Item::with(['category', 'unit'])->where('sku', $sku)->first();
        if (!$item) return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
         $item = Item::find($id);
         if (!$item) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

         $item->update($request->all());
         return response()->json(['success' => true, 'message' => 'Barang update', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        if (!$item) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $item->delete();
        return response()->json(['success' => true, 'message' => 'Barang dihapus']);
    }
}
