<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
   {
      Schema::create('categories', function (Blueprint $table) {
         $table->smallInteger('id')->unsigned()->primary()->autoIncrement();
         $table->string('name', 64);
         $table->timestamps();
      });

      Schema::create('products', function (Blueprint $table) {
         $table->mediumInteger('id')->unsigned()->primary()->autoIncrement();
         $table->string('name', 255);
         $table->text('description')->nullable();
         $table->decimal('price', 7, 2);
         $table->smallInteger('category_id')->unsigned()->nullable();
         $table->timestamps();
         $table->softDeletes();

         // Keep the product data even if the category is deleted.
         $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('set null');

         // Improve searching performance
         $table->index('name');
      });

      Schema::create('inventory', function (Blueprint $table) {
         $table->mediumInteger('id')->unsigned()->primary()->autoIncrement();
         $table->Integer('stock')->unsigned()->default(0);
         $table->mediumInteger('product_id')->unsigned();
         $table->timestamps();

         // Delete the following in the inventory if the product is deleted.
         $table->foreign('product_id')
            ->references('id')->on('products')
            ->onDelete('cascade');

         // Improve searching performance
         $table->index('product_id');
      });
   }

   public function down(): void
   {
      Schema::dropIfExists('inventory');
      Schema::dropIfExists('products');
      Schema::dropIfExists('categories');
   }
};
