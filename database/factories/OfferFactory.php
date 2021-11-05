<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = $this->faker->randomElement([1,2,3]);
        $offer_smiles = $this->faker->randomElement([100,200,300]);
        $status = $this->faker->randomElement(['Redeemed','Active','Active']);

        return [
            'user_id' => $user_id,
            'offer_image' => '', // password
            'offer_name' => $this->faker->name(),
            'offer_smiles' => $offer_smiles,
            'offer_starting_date' => Carbon::now()->subDays(500)->format('Y-m-d'),
            'offer_starting_time' => Carbon::now()->subDays(500)->format('g:i A'),
            'offer_ending_date' =>  Carbon::now()->addDays(1500)->format('Y-m-d'),
            'offer_ending_time' => Carbon::now()->addDays(1500)->format('g:i A'),
            'offer_condition' =>  $this->faker->paragraph(),
            'status' => $status,
        ];
    }
}
