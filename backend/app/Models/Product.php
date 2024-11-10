<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id'
    ];

    protected $hidden  = ['created_at', 'updated_at', 'deleted_at'];

    // Inverse 1-M relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 1-1 relationship with Inventory
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public static function getRecentlyAdded($value = 5)
    {
        return self::latest()->take($value)->get();
    }

    public static function getRecentlyUpdated($value = 5)
    {
        return DB::table('products AS product')
            ->join('inventory', 'product.id', '=', 'inventory.product_id')
            ->select(
                'product.id',
                'product.name',
                'product.description',
                'product.price',
                'product.category_id'
            )
            ->whereNull('product.deleted_at')
            ->orderBy(DB::raw('
                CASE
                    WHEN product.updated_at > inventory.updated_at THEN product.updated_at
                    ELSE inventory.updated_at
                END'), 'desc')
            ->take($value)
            ->get();
    }
}
