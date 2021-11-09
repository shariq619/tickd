<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserBadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $uid = 1;
        $bid = 2;
        return [
            'user_id' => $uid,
            'badge_id' => $bid
        ];
    }
}
