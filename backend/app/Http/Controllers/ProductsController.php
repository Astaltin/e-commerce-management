<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['inventory', 'category'])->get();

            return response()->json(['products' => $products]);
        } catch (\Throwable $th) {
            Log::error('ProductsController::index(): ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to load products.'
            ], 500);
        }
    }
}
