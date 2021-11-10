<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StickerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['0','1']);

        return [
            'name' => $this->faker->name(),
            'status' => $status,
        ];
    }
}
