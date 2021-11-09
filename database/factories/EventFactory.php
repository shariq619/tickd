<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$user_id = $this->faker->randomElement([1,2,3]);
        $bid = 2;
        $status = $this->faker->randomElement(['Coming','Active','Past','Active']);

        return [
            'business_id' => $bid,
            'event_image' => '', // password
            'event_name' => $this->faker->name(),
            'event_starting_date' => Carbon::now()->subDays(500)->format('Y-m-d'),
            'event_starting_time' => Carbon::now()->subDays(500)->format('g:i A'),
            'event_ending_date' =>  Carbon::now()->addDays(1500)->format('Y-m-d'),
            'event_ending_time' => Carbon::now()->addDays(1500)->format('g:i A'),
            'event_description' =>  $this->faker->paragraph(),
            'status' => $status,
        ];
    }
}
