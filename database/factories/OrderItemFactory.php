<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {

        return [
            'order_id' => Order::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'price' => $this->faker->numberBetween(10, 1000),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
