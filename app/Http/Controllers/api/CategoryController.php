<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET: Ambil Semua Kategori
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Category::latest()->get()
        ]);
    }

    // POST: Tambah Kategori Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ]);
    }

    // GET: Detail Kategori (Optional)
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        return response()->json(['success' => true, 'data' => $category]);
    }

    // PUT: Update Kategori
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $request->validate([
            'name' => 'required|unique:categories,name,'.$id
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => $category
        ]);
    }

    // DELETE: Hapus Kategori
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
