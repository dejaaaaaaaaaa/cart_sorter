<?php

namespace Database\Seeders;

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Mleko',
            'Hleb',
            'Rosa voda',
            'Jogurt',
            'Kiselo mleko',
            'Paradajz',
            'Krastavci',
        ];

        foreach ($products as $product){
            Product::factory()->create([
                'name' => $product
            ]);
        }
    }
}
