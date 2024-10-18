<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name'];

    // 1-M relationship with Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
