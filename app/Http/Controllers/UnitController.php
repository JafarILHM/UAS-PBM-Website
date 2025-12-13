<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();
        return view('units.index', compact('units'));
    }

    // HALAMAN TAMBAH
    public function create()
    {
        return view('units.create');
    }

    // PROSES SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'symbol' => 'required|string|max:10',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    // HALAMAN EDIT
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    // PROSES UPDATE
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'symbol' => 'required|string|max:10',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Satuan berhasil dihapus');
    }
}
