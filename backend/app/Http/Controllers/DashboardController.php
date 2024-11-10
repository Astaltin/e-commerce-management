<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                // product metrics
                'total_products_active' => Product::count(),
                'total_products_deleted' => Product::onlyTrashed()->count(),
                'total_products_by_category' => Category::getTotalProducts(),
                'low_stock_products' => Inventory::getLowStock(),
                'out_of_stock_products' => Inventory::getOutOfStock(),

                // inventory metrics
                'total_stocks' => Inventory::count(),
                'total_stocks_value' => Inventory::getTotalStocksValue(),

                // category insights
                'total_categories' => Category::count(),
                'total_inventory_values_by_category' => Inventory::getTotalValuesByCategory(),

                // alerts
                'recently_added_products' => Product::getRecentlyAdded(3),
                'recently_updated_products' => Product::getRecentlyUpdated(3),
            ]);
        } catch (\Throwable $th) {
            Log::error('DashboardController::index(): ' . $th->getMessage());

            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
