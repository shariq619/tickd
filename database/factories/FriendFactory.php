<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $u = User::where('user_type','user')->where('id','!=',1)->get()->pluck('id')->toArray();
        $friends = $this->faker->randomElement($u);



        return [
            'user_id' => 1,
            'friend_id' => $friends
        ];

    }
}
