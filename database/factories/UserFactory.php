<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $gender = $this->faker->randomElement(['male','female']);
        $status = $this->faker->randomElement(['0','1']);
        $user_type = $this->faker->randomElement(['user','business']);

        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->name(),
            'user_type' => $user_type,
            'bio' => $this->faker->paragraph(),
            'referal_code' =>  Str::random(10),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),

            'nationality' => $this->faker->name(),
            'dob' => Carbon::now()->subDays(1500)->format('Y-m-d'),
            'mobile' => $this->faker->phoneNumber(),
            'gender' => $gender,
            'status' => $status,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
