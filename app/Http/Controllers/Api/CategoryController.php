<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::where('status', 1)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }
}
