<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'category_name',
        'name',
        'slug',
        'description',
        'short_description',
        'image',
        'gallery_images',
        'price',
        'offer_price',
        'rating',
        'review_count',
        'stock',
        'sku',
        'is_featured',
        'is_popular',
        'is_latest',
        'is_trending',
        'is_flash_sale',
        'status'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_latest' => 'boolean',
        'is_trending' => 'boolean',
        'is_flash_sale' => 'boolean',
        'status' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }
}
