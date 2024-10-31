<?php

namespace App\Http\Controllers\api;

use App\Models\Flavor;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => Product::all(),
            'flavors' => Flavor::all(),
        ]);
    }

    public function create()
    {
        return response()->json([
            'message' => 'Create form endpoint for products',
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'thumbnail' => ['required', 'image'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'price' => ['required'],
        ]);

        $attributes['thumbnail'] = $request->file('thumbnail')->store('thumbnails');

        $product = Product::create($attributes);

        return response()->json([
            'message' => "{$attributes['name']} added successfully",
            'product' => $product,
        ]);
    }

    public function edit(Product $product)
    {
        return response()->json([
            'product' => $product,
            'message' => 'Edit form endpoint for products',
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $attributes = $request->validate([
            'thumbnail' => ['image'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'price' => ['required'],
        ]);

        if ($attributes['thumbnail'] ?? false) {
            $attributes['thumbnail'] = $request->file('thumbnail')->store('thumbnails');
        }

        $product->update($attributes);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => "{$product->name} deleted successfully",
        ]);
    }
}
