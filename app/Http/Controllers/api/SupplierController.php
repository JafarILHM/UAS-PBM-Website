<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Supplier::latest()->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'contact' => 'nullable',
            'address' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
        if (!$supplier) return response()->json([
            'success'=>false,
            'message'=>'Data tidak ditemukan'
        ], 404);
        return response()->json(['success' => true, 'data' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier->update($request->all());
        return response()->json(['success' => true, 'message' => 'Supplier diupdate', 'data' => $supplier]);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $supplier->delete();
        return response()->json(['success' => true, 'message' => 'Supplier dihapus']);
    }
}
