<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollInfosTable extends Migration
{

    public function up()
    {
        Schema::create('poll_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('poll_infos');
    }
}
