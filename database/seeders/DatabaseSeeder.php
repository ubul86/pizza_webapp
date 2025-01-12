<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ProductTableSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            CategoryTableSeeder::class,
            ProductTableSeeder::class,
            OrderTableSeeder::class,
            OrderItemTableSeeder::class,
            ImageSeeder::class,
            ProductImageSeeder::class,
        ]);
    }
}
