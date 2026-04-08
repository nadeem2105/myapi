<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id');
            $table->string('category_name')->nullable();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->longText('short_description')->nullable();

            $table->string('image')->nullable();
            $table->json('gallery_images')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('offer_price', 10, 2)->nullable();

            $table->float('rating')->default(0);
            $table->integer('review_count')->default(0);

            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_latest')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
