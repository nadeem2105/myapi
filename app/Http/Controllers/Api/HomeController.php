<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\RecentlyViewedProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $user = auth()->user();

        $banners = Banner::where('status', 1)
            ->orderBy('position', 'asc')
            ->get();

        $categories = Category::where('status', 1)
            ->get();

        $featuredProducts = Product::where('status', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(10)
            ->get();

        $latestProducts = Product::where('status', 1)
            ->latest()
            ->take(10)
            ->get();

        $popularProducts = Product::where('status', 1)
            ->where('is_popular', 1)
            ->latest()
            ->take(10)
            ->get();

        $trendingProducts = Product::where('status', 1)
            ->where('is_trending', 1)
            ->latest()
            ->take(10)
            ->get();

        $flashSaleProducts = Product::where('status', 1)
            ->where('is_flash_sale', 1)
            ->latest()
            ->take(10)
            ->get();

        $recentlyViewedProducts = [];

        if ($user) {
            $recentlyViewedProducts = RecentlyViewedProduct::where('user_id', $user->id)
                ->with('product')
                ->latest()
                ->take(10)
                ->get()
                ->pluck('product');
        }

        return response()->json([
            'status' => true,
            'message' => 'Home data fetched successfully',
            'data' => [
                'banners' => $banners,
                'categories' => $categories,
                'featured_products' => $featuredProducts,
                'latest_products' => $latestProducts,
                'popular_products' => $popularProducts,
                'trending_products' => $trendingProducts,
                'flash_sale_products' => $flashSaleProducts,
                'recently_viewed_products' => $recentlyViewedProducts,
            ]
        ]);
    }
}
