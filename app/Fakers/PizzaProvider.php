<?php

namespace App\Fakers;

use Faker\Provider\Base;

class PizzaProvider extends Base
{
    protected static array $pizzaNames = [
        'Margherita',
        'Pepperoni',
        'Hawaiian',
        'BBQ Chicken',
        'Four Cheese',
        'Veggie Deluxe',
        'Spicy Mexican',
        'Seafood Special',
        'Mediterranean',
        'Meat Feast',
    ];

    public static function pizzaName(): string
    {
        return static::randomElement(static::$pizzaNames);
    }
}
