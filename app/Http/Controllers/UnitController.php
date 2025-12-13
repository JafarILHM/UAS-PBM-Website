<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index() {
        $units = Unit::latest()->get();
        return view('units.index', compact('units'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:units',
            'symbol' => 'required|unique:units'
        ]);
        Unit::create($request->all());
        return back()->with('success', 'Satuan berhasil ditambahkan');
    }

    public function update(Request $request, Unit $unit) {
        $request->validate([
            'name' => 'required|unique:units,name,'.$unit->id,
            'symbol' => 'required|unique:units,symbol,'.$unit->id
        ]);
        $unit->update($request->all());
        return back()->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy(Unit $unit) {
        $unit->delete();
        return back()->with('success', 'Satuan berhasil dihapus');
    }
}
