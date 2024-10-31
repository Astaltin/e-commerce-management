<?php

namespace App\Http\Controllers\api;

use App\Models\Flavor;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => Product::all(),
            'flavors' => Flavor::all(),
            'orders' => Order::all(),
        ]);
    }
}
