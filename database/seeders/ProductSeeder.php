<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productNames = ["a", "b", "c"];
        foreach ($productNames as $name) {
            Product::factory()->create(["name" => $name]);
        }
    }
}
