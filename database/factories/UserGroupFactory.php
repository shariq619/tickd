<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bid = $this->faker->randomElement([1,2,3]);
        $uid = $this->faker->randomElement([1,2,3]);
        return [
            'badge_id' => $bid,
            'user_id' => $uid,
            'name' => $this->faker->name()
        ];
    }
}
