<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollsTable extends Migration
{

    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->longText('options')->nullable();
            $table->longText('vote')->nullable();
            $table->unsignedBigInteger('status')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
