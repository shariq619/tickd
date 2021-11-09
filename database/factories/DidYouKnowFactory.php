<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DidYouKnowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$business_id = $this->faker->randomElement([1,2,3]);
        $bid = 2;
        $status = $this->faker->randomElement([0,1]);

        return [
            'business_id' => $bid,
            'text' => $this->faker->paragraph(),
            'status' => $status,
        ];
    }
}
