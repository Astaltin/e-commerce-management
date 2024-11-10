<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // for creating dummy users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => 'admin'
        ]);

        // for creating dummy products
        $categories = Category::factory()->count(random_int(4, 6))->create();
        Product::factory()->count(random_int(19, 99))
            ->create()->each(function ($product) use ($categories) {
                $product->category_id = $categories->random()->id;
                $product->save();

                Inventory::factory()->create(['product_id' => $product->id]);
            });
    }
}
