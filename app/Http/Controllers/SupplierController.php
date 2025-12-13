<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Supplier::create($request->all());
        return back()->with('success', 'Supplier berhasil ditambahkan');
    }

    public function update(Request $request, Supplier $supplier) {
        $request->validate(['name' => 'required']);
        $supplier->update($request->all());
        return back()->with('success', 'Supplier berhasil diupdate');
    }

    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus');
    }
}
