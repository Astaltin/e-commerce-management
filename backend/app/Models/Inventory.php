<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

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
