<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Supplier::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'nullable',
            'address' => 'nullable'
        ]);

        $supplier = Supplier::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan',
            'data' => $supplier
        ]);
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        return response()->json(['success' => true, 'data' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $request->validate([
            'name' => 'required',
        ]);

        $supplier->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil diupdate',
            'data' => $supplier
        ]);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil dihapus'
        ]);
    }
}
