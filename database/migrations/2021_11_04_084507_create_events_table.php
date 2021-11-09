<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('event_image');
            $table->string('event_name');
            $table->string('event_starting_date');
            $table->string('event_starting_time');
            $table->string('event_ending_date');
            $table->string('event_ending_time');
            $table->text('event_description');
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
        Schema::dropIfExists('events');
    }
}
