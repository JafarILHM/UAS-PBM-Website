<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    // HALAMAN TAMBAH
    public function create()
    {
        return view('suppliers.create');
    }

    // PROSES SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    // HALAMAN EDIT
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    // PROSES UPDATE
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus');
    }
}
