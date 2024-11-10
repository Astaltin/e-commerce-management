<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    use HasFactory;


    protected $fillable = ['stock', 'product_id'];

    protected $hidden  = ['created_at', 'updated_at',];

    protected $table = 'inventory';

    // Inverse 1-1 relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getLowStock(): int
    {
        return self::getStockStatusByThreshold(15);
    }

    public static function getOutOfStock(): int
    {
        return self::getStockStatusByThreshold(0);
    }

    public static function getTotalStocksValue(): string
    {
        return self::join('products AS product', 'inventory.product_id', '=', 'product.id')
            ->select(DB::raw('SUM(stock * price) AS total_stocks_value'))
            ->whereNull('product.deleted_at')
            ->value('total_stocks_value');
    }

    public static function getTotalValuesByCategory()
    {
        return self::join('products AS product', 'inventory.product_id', '=', 'product.id')
            ->select(
                'category_id AS id',
                DB::raw('SUM(price * stock) AS value')
            )
            ->whereNull('product.deleted_at')
            ->groupBy('category_id')
            ->get();
    }

    private static function getStockStatusByThreshold($value): int
    {
        return self::join('products AS product', 'inventory.product_id', '=', 'product.id')
            ->select(DB::raw('COUNT(inventory.stock) as stock_count'))
            ->where(function ($query) use ($value) {
                $query->where('stock', '=', $value)
                    ->orWhere('stock', '<', $value);
            })
            ->whereNull('product.deleted_at')
            ->value('stock_count');
    }
}
