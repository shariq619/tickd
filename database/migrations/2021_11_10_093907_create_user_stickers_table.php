<?php

use App\Models\UserSticker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stickers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sticker_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        UserSticker::create([
            'sticker_id' => 1,
            'user_id' => 1
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_stickers');
    }
}
