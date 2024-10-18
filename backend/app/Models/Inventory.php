<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $casts = [
        'stock' => 'integer',
        'product_id' => 'integer'
    ];

    protected $fillable = [
        'stock',
        'product_id'
    ];

    // Inverse 1-1 relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
