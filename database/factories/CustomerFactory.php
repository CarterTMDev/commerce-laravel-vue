<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $_addr_1 = "";
        $_addr_1 .= $this->faker->buildingNumber();
        $_addr_1 .= " " . $this->faker->streetName();
        $_addr_1 .= " " . $this->faker->streetSuffix();

        $_addr_2 = null;
        if (rand(0, 3) == 0)
        {
            $_addr_2 = $this->faker->secondaryAddress();
        }

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'address_1' => $_addr_1,
            'address_2' => $_addr_2,
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zipcode' => $this->faker->postcode(),
            'country' => $this->faker->country()
        ];
    }
}
