<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $products = \App\Models\Product::all();
        $images = \App\Models\Image::all();

        foreach ($products as $product) {
            $imageId = $images->random()->id;
            DB::table('products_images')->insert([
                'product_id' => $product->id,
                'image_id' => $imageId,
                'first' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
