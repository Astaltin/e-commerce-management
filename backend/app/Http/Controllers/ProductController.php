<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $validatedData = (object) $request->validated();

        try {
            $product = DB::transaction(function () use ($validatedData) {
                $category = Category::query()->firstOrCreate([
                    'name' => $validatedData->category
                ]);

                $product = Product::query()->create([
                    'name' => $validatedData->name,
                    'description' => $validatedData->description,
                    'price' => $validatedData->price,
                    'stock' => $validatedData->stock,
                    'category_id' => $category->id
                ]);

                Inventory::query()->create([
                    'stock' => $validatedData->stock,
                    'product_id' => $product->id
                ]);

                return $product;
            });

            return response()->json([
                'message' => 'Product created successfully.',
                'product' => $product
            ], 201);
        } catch (\Throwable $th) {
            Log::error('ProductController::store(): ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create product.'
            ], 500);
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = (object) $request->validated();

        try {
            DB::transaction(function () use ($validatedData, $product) {
                $product->update([
                    'name' => $validatedData->name,
                    'description' => $validatedData->description,
                    'price' => $validatedData->price
                ]);

                $product->inventory()->updateOrCreate([], [
                    'stock' => $validatedData->stock
                ]);

                $category = Category::query()->firstOrCreate([
                    'name' => $validatedData->category
                ]);
                $product->category()->associate($category);
                $product->save();
            });

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            Log::error('ProductController::update(): ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update product.'
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully.'
            ]);
        } catch (\Throwable $th) {
            Log::error('ProductController::destroy(): ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create product.'
            ], 500);
        }
    }
}
