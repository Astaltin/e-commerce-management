<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
   use SoftDeletes;

   protected $table = 'products';

   protected $casts = [
      'price' => 'decimal:2',
      'category_id' => 'integer'
   ];

   protected $fillable = [
      'name',
      'description',
      'price',
      'category_id'
   ];

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
}
