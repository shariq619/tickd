<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(13)->create();
         \App\Models\Event::factory(5)->create();
         \App\Models\Challenge::factory(5)->create();
         \App\Models\Offer::factory(5)->create();
         \App\Models\Badge::factory(5)->create();
         \App\Models\UserBadge::factory(5)->create();
         \App\Models\UserGroup::factory(5)->create();
         \App\Models\DidYouKnow::factory(5)->create();
    }
}
