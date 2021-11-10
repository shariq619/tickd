<?php

use App\Models\BusinessSticker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_stickers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sticker_id');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();
        });

        BusinessSticker::create([
            'sticker_id' => 1,
            'business_id' => 2
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_stickers');
    }
}
