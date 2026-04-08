<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::insert([
            [
                'title' => 'Big Sale',
                'image' => 'uploads/banners/banner1.jpg',
                'type' => 'home',
                'link' => null,
                'status' => 1,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Flash Sale',
                'image' => 'uploads/banners/banner2.jpg',
                'type' => 'home',
                'link' => null,
                'status' => 1,
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
