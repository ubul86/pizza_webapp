<?php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Fakers\PizzaProvider;
use App\Models\Category;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {

        $this->faker->addProvider(new PizzaProvider($this->faker));

        return [
            'name' => $this->faker->pizzaName(),
            'description' => $this->faker->sentence,
            'category_id' => Category::all()->random()->id,
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
