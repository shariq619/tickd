<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('offer_image');
            $table->string('offer_name');
            $table->string('offer_starting_date');
            $table->string('offer_starting_time');
            $table->string('offer_ending_date');
            $table->string('offer_ending_time');
            $table->integer('offer_smiles')->nullable();
            $table->text('offer_condition');
            $table->string('status')->default('Active')->comment = 'Redeemed,Inactive';
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
        Schema::dropIfExists('offers');
    }
}
