<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bot_states', function (Blueprint $table) {
            $table->id();

            // last_update_id - для хранения максимального update_id
            $table->unsignedBigInteger('last_update_id')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_states');
    }
};
