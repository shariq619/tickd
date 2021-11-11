<?php

use App\Models\UserBusinessType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBusinessTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_business_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();
        });

       UserBusinessType::create([
            'user_id' => 1,
            'business_id' => 2
       ]);

        UserBusinessType::create([
            'user_id' => 1,
            'business_id' => 3
        ]);

    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_business_types');
    }
}
