<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{

    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('size');
            $table->string('image')->nullable();
            $table->string('slot')->nullable();
            $table->text('embed_code')->nullable();
            $table->text('redirect_url')->nullable();
            $table->unsignedBigInteger('click')->default(0);
            $table->unsignedBigInteger('impression')->default(0);
            $table->unsignedBigInteger('status')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('advertisements');
    }
}
