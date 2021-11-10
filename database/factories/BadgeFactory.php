<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$bid = $this->faker->randomElement(User::where('user_type','business')->first()->pluck('id'));
        $bid = 2;
        //$cid = $this->faker->randomElement([1,2,3]);
        return [
            'business_id' => $bid,
            //'city_id' => $cid,
            'name' => $this->faker->name()
        ];
    }
}
