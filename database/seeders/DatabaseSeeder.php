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
         \App\Models\User::factory(4)->create();
         \App\Models\Event::factory(5)->create();
         \App\Models\Challenge::factory(5)->create();
         \App\Models\Offer::factory(5)->create();
    }
}
