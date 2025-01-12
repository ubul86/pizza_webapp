<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsImagesTable extends Migration
{
    public function up()
    {
        Schema::create('products_images', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('image_id')->constrained('images')->onDelete('cascade');
            $table->boolean('first')->default(false);
            $table->primary(['product_id', 'image_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products_images');
    }
}
