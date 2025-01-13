<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ImageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $images = ['pizza1.png', 'pizza2.png'];

        foreach ($images as $image) {
            DB::table('images')->insert([
                'path' => 'images/' . $image,
                'type' => Image::TYPE_PUBLIC,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
