<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = $this->faker->randomElement([1,2,3]);
        $bid = 2;
        $status = $this->faker->randomElement(['Coming','Active','Past','Active']);
        $challenge_type = $this->faker->randomElement(['Simple','Complex']);
        $challenge_days = ['Monday','Tuesday','Wednesday'];
        $is_completed = $this->faker->randomElement([0,1]);

        return [
            'business_id' => $bid,
            'challenge_image' => '',
            'challenge_name' => $this->faker->name(),
            'challenge_type' => $challenge_type,
            'days' => $challenge_days,
            'start_time' => Carbon::now()->format('g:i A'),
            'end_time' => Carbon::now()->addSecond()->format('g:i A'),
            'challenge_starting_date' => Carbon::now()->subDays(500)->format('Y-m-d'),
            'challenge_starting_time' => Carbon::now()->subDays(500)->format('g:i A'),
            'challenge_ending_date' =>  Carbon::now()->addDays(1500)->format('Y-m-d'),
            'challenge_ending_time' => Carbon::now()->addDays(1500)->format('g:i A'),
            'is_completed' => $is_completed,
            'status' => $status,
        ];
    }
}
