<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('challenge_image');
            $table->string('challenge_name');
            $table->string('challenge_type')->default('Simple')->comment = 'Simple,Complex';

            $table->json('days');

            $table->string('start_time');
             $table->string('end_time');

            $table->integer('challenge_smiles')->nullable();
            $table->string('challenge_reward')->nullable();
            $table->string('challenge_no_of_spots')->nullable();

            $table->boolean('is_completed')->default(false);

            $table->string('challenge_starting_date')->nullable();
            $table->string('challenge_starting_time')->nullable();
            $table->string('challenge_ending_date')->nullable();
            $table->string('challenge_ending_time')->nullable();


            $table->string('status')->default('Coming')->comment = 'Coming,Active,Past,Inactive';

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenges');
    }
}
