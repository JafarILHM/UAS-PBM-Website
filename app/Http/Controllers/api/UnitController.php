<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Unit::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'symbol' => 'required'
        ]);

        $unit = Unit::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil ditambahkan',
            'data' => $unit
        ]);
    }

    public function show($id)
    {
        $unit = Unit::find($id);
        if (!$unit) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        return response()->json(['success' => true, 'data' => $unit]);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);
        if (!$unit) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $request->validate([
            'name' => 'required',
            'symbol' => 'required'
        ]);

        $unit->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil diupdate',
            'data' => $unit
        ]);
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        if (!$unit) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil dihapus'
        ]);
    }
}
