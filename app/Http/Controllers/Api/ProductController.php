<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\RecentlyViewedProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categoryProducts($category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $products = Product::where('category_id', $category_id)
            ->where('status', 1)
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Category wise products fetched successfully',
            'category' => $category,
            'products' => $products
        ]);
    }

    public function productDetails($id)
    {
        $product = Product::with([
            'category',
            'specifications',
            'variants'
        ])
        ->where('id', $id)
        ->where('status', 1)
        ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if (auth()->check()) {
            RecentlyViewedProduct::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id
                ],
                []
            );
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->take(10)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Product details fetched successfully',
            'product' => $product,
            'related_products' => $relatedProducts
        ]);
    }
}
