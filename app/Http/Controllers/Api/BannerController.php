<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    public function banners()
    {
        $banners = Banner::where('status', 1)
            ->orderBy('position', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Banners fetched successfully',
            'data' => $banners
        ]);
    }
}
