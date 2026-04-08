<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/sendOtp', [AuthController::class, 'sendOtp']);
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
Route::post('/checkuser', [AuthController::class, 'checkuser']);
Route::post('/updateProfile',[AuthController::class, 'updateProfile']);
Route::get('/home', [HomeController::class, 'home']);
Route::get('/banners', [BannerController::class, 'banners']);

Route::get('/categories', [CategoryController::class, 'categories']);

Route::get('/category-products/{category_id}', [ProductController::class, 'categoryProducts']);

Route::get('/product-details/{id}', [ProductController::class, 'productDetails']);


Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);

Route::get('/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/home', [HomeController::class, 'home']);
    Route::get('/product-details/{id}', [ProductController::class, 'productDetails']);
});