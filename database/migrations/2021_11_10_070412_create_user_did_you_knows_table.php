<?php

use App\Models\UserDidYouKnow;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDidYouKnowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_did_you_knows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('did_you_know_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->boolean('is_locked')->default(true);
            $table->timestamps();
        });

        UserDidYouKnow::create(
            ['did_you_know_id' => 1,'user_id' => 1,'business_id'=>2]
        );

        UserDidYouKnow::create(
            ['did_you_know_id' => 2,'user_id' => 1,'business_id'=>2]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_did_you_knows');
    }
}
