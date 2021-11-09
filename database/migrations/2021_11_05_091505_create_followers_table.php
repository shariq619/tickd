<?php

use App\Models\Follower;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('follower_id')->unsigned();
            $table->integer('leader_id')->unsigned();
            $table->timestamps();
        });

        Follower::create([
           'follower_id' => 2,
           'leader_id' => 1,
        ]);

        Follower::create([
            'follower_id' => 3,
            'leader_id' => 1,
        ]);

        Follower::create([
            'follower_id' => 1,
            'leader_id' => 2,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
