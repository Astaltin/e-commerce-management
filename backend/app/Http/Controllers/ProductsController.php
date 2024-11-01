<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with(['inventory', 'category'])->get();

        return response()->json(['products' => $products]);
    }
}
