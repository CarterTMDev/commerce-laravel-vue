<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'isShipped' => rand(0, 1) == 0 ? true : false,
            'initial_cost' => rand(5, 20) + rand(0, 99) / 100,// TODO: generate this with products_purchased table
            'shipping_cost' => rand(5, 20) + rand(0, 99) / 100
        ];
    }
}
