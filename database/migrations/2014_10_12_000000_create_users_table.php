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
            $table->string('avatar')->default('no-image.png');
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
