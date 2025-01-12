<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {

        return [
            'name' => $this->faker->name,
            'email_address' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber
        ];
    }
}
