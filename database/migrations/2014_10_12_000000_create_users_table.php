<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('referal_code')->unique()->nullable();

            $table->string('device_token')->nullable();
            $table->string('device_type')->nullable();

            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('business_type_id')->nullable();
            $table->string('avatar')->default('no-image.png');
            $table->string('user_type')->default('user')->comment('user,business');
            $table->string('name');
            $table->string('username');
            $table->text('bio');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nationality');
            $table->string('dob');
            $table->string('mobile');
            $table->string('gender');
            $table->boolean('status')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });

        $user = new User;
        $user->referal_code = Str::random(10);

        $user->device_token = '';

        $user->name = "tickd";
        $user->username = "tickd";
        $user->bio = "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.";
        $user->email = "tickd@email.com";
        $user->email_verified_at = now();
        $user->password = bcrypt("password");

        $user->nationality = "British";
        $user->dob = Carbon::now()->subDays(3000)->format('Y-m-d');
        $user->mobile = "442045771138";
        $user->gender = "male";
        $user->status = 1;

        $user->remember_token = Str::random(10);
        $user->save();

        // business user
        $business = new User;
        $business->referal_code = Str::random(10);
        $business->city_id = 1;
        $business->business_type_id = 1;
        $business->name = "tickd_business";
        $business->user_type = "business";
        $business->username = "tickd_business";
        $business->bio = "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.";
        $business->email = "tickd_business@email.com";
        $business->email_verified_at = now();
        $business->password = bcrypt("password");

        $business->nationality = "British";
        $business->dob = Carbon::now()->subDays(3000)->format('Y-m-d');
        $business->mobile = "442045771138";
        $business->gender = "male";
        $business->status = 1;

        $business->remember_token = Str::random(10);
        $business->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
