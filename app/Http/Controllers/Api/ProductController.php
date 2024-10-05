<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        return Product::all();
    }

    // Menambahkan produk baru
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // Menampilkan detail produk berdasarkan id
    public function show(Product $product)
    {
        return $product;
    }

    // Memperbarui produk berdasarkan id
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    // Menghapus produk berdasarkan id
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }

    // Menambahkan logika pembelian produk
    public function buy(Product $product)
    {
        // Cek apakah stok masih tersedia
        if ($product->stock > 0) {
            // Kurangi stok sebesar 1
            $product->stock -= 1;
            $product->save(); // Simpan perubahan stok

            return response()->json([
                'message' => 'Pembelian berhasil!',
                'product' => $product
            ], 200);
        } else {
            // Jika stok habis, beri pesan error
            return response()->json([
                'message' => 'Stok habis!'
            ], 400); // 400 Bad Request
        }
    }

    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'productIds' => 'required|array',
            'productIds.*' => 'exists:products,id', // Pastikan ID ada di tabel products
        ]);

        foreach ($validated['productIds'] as $id) {
            $product = Product::find($id);
            if ($product->stock > 0) {
                $product->stock -= 1; // Kurangi stok
                $product->save(); // Simpan perubahan
            } else {
                return response()->json(['message' => "Stok produk dengan ID $id tidak cukup."], 400);
            }
        }

        return response()->json(['message' => 'Pembelian berhasil!'], 200);
    }
}
