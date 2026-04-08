<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        ProductVariant::insert([
            [
                'product_id' => 2,
                'variant_type' => 'Storage',
                'variant_value' => '128GB',
                'price' => 79999,
                'offer_price' => 74999,
                'stock' => 10,
                'image' => 'uploads/products/iphone15_128gb.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'variant_type' => 'Storage',
                'variant_value' => '256GB',
                'price' => 89999,
                'offer_price' => 84999,
                'stock' => 8,
                'image' => 'uploads/products/iphone15_256gb.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
