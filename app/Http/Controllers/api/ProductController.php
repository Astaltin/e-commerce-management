<?php

namespace App\Http\Controllers\api;

use App\Models\Flavor;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => Product::all(),
            'flavors' => Flavor::pluck('name', 'id'),
        ]);
    }

    public function search(Request $request)
    {
        return response()->json([
            'products' => Product::latest()->filter($request->only('search'))->get(),
            'search' => $request->input('search'),
        ]);
    }
}
