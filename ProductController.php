<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:products',
            'nama' => 'required|unique:products',
            'harga' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($request->only(['kode', 'nama', 'harga', 'stock']));

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'kode' => 'required|unique:products,kode,' . $product->id,
            'nama' => 'required|unique:products,nama,' . $product->id,
            'harga' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $product->update($request->only(['kode', 'nama', 'harga', 'stock']));

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}