<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['0','1']);
        $category_id = $this->faker->randomElement([1,2,3,4,5]);

        return [
            'category_id' => $category_id,
            'name' => $this->faker->name(),
            'status' => $status,
        ];
    }
}
