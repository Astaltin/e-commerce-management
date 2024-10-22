<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['inventory', 'category'])->get();

            return response()->json([
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load products. Please try again later.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function create(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:64',
            'stock' => 'required|integer|min:0',
        ]);

        $category = Category::firstOrCreate(['name' => $request->category]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $category->id,
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'stock' => $request->stock
        ]);

        return response()->json(['message' => 'Product inserted successfully', 'product' => $product], 201);
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:64',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $product = Product::findOrFail($id);

            $category = Category::firstOrCreate([
                'name' => $request['category'],
            ]);

            $product->update([
                'name' => $request['name'],
                'description' => $request['description'],
                'price' => $request['price'],
                'category_id' => $category->id,
            ]);

            $inventory = $product->inventory;
            $inventory->update([
                'stock' => $request['stock'],
            ]);

            return redirect()->route('inventory')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::with(['inventory', 'category'])->findOrFail($id);

            if ($product->inventory) {
                $product->inventory()->delete();
            }

            $category = $product->category;

            $product->forceDelete();

            if ($category) {
                $otherProducts = Product::where('category_id', $category->id)->where('id', '!=', $product->id)->exists();

                if (!$otherProducts) {
                    $category->delete();
                }
            }

            return redirect()->route('inventory')->with('success', 'Product and its associated category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('inventory')->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
