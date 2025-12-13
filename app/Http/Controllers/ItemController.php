<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Tampilkan daftar barang
     */
    public function index()
    {
        // Ambil data barang beserta nama kategorinya
        $items = Item::with('category')->latest()->get();
        return view('items.index', compact('items'));
    }

    /**
     * Tampilkan form tambah barang
     */
    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();

        // Generate SKU otomatis (Opsional, bisa diganti user)
        $autoSku = 'BRG-' . strtoupper(Str::random(6));

        return view('items.create', compact('categories', 'units', 'autoSku'));
    }

    /**
     * Simpan barang baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:items',
            'name' => 'required',
            'category_id' => 'required',
            'unit' => 'required',
        ]);

        // Jika barcode kosong, samakan dengan SKU
        $barcode = $request->barcode ?? $request->sku;

        Item::create([
            'sku' => $request->sku,
            'barcode' => $barcode,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'stock' => 0, // Barang baru stoknya 0, nanti diisi lewat "Barang Masuk"
            'stock_minimum' => $request->stock_minimum ?? 5,
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('items.edit', compact('item', 'categories', 'units'));
    }

    /**
     * Update data barang
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'sku' => 'required|unique:items,sku,'.$item->id,
            'name' => 'required',
            'category_id' => 'required',
            'unit' => 'required',
        ]);

        $item->update([
            'sku' => $request->sku,
            'barcode' => $request->barcode ?? $request->sku,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'stock_minimum' => $request->stock_minimum,
        ]);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui');
    }

    /**
     * Hapus barang
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus');
    }
}
