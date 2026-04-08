<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSpecification;

class ProductSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        ProductSpecification::insert([
            [
                'product_id' => 2,
                'group' => 'Display',
                'title' => 'Size',
                'value' => '6.1 inch',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'group' => 'Performance',
                'title' => 'RAM',
                'value' => '8GB',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'group' => 'Storage',
                'title' => 'Internal Storage',
                'value' => '256GB',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
