<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    protected $hidden  = ['created_at', 'updated_at', 'deleted_at'];

    // 1-M relationship with Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getTotalProducts()
    {

        return DB::table('categories AS category')
            ->join('products AS product', 'product.category_id', '=', 'category.id')
            ->select('category.name', DB::raw('COUNT(product.id) AS count'))
            ->whereNull('product.deleted_at')
            ->groupBy('category.id')
            ->get();
    }
}
