<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'category_id' => 1,
                'category_name' => 'Electronics',
                'name' => 'Samsung Smart TV',
                'slug' => 'samsung-smart-tv',
                'description' => 'Samsung 55 inch Ultra HD Smart TV',
                'short_description' => '55 inch Smart TV',
                'image' => 'uploads/products/tv.jpg',
                'gallery_images' => json_encode([
                    'uploads/products/tv1.jpg',
                    'uploads/products/tv2.jpg'
                ]),
                'price' => 55000,
                'offer_price' => 49999,
                'rating' => 4.5,
                'review_count' => 120,
                'stock' => 20,
                'sku' => 'TV001',
                'is_featured' => 1,
                'is_popular' => 1,
                'is_latest' => 1,
                'is_trending' => 1,
                'is_flash_sale' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'category_name' => 'Mobiles',
                'name' => 'iPhone 15',
                'slug' => 'iphone-15',
                'description' => 'Latest Apple iPhone 15',
                'short_description' => 'Apple flagship smartphone',
                'image' => 'uploads/products/iphone15.jpg',
                'gallery_images' => json_encode([
                    'uploads/products/iphone15_1.jpg',
                    'uploads/products/iphone15_2.jpg'
                ]),
                'price' => 89999,
                'offer_price' => 84999,
                'rating' => 4.8,
                'review_count' => 300,
                'stock' => 15,
                'sku' => 'IPH15',
                'is_featured' => 1,
                'is_popular' => 1,
                'is_latest' => 1,
                'is_trending' => 1,
                'is_flash_sale' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
