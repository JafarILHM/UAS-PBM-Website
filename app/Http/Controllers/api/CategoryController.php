<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Category::latest()->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($request->all());
        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan', 'data' => $category]);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->all());
        return response()->json(['success' => true, 'message' => 'Kategori diupdate', 'data' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['success'=>false, 'message'=>'Data tidak ditemukan'], 404);

        $category->delete();
        return response()->json(['success' => true, 'message' => 'Kategori dihapus']);
    }
}
