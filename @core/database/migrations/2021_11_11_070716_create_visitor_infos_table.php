<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_infos', function (Blueprint $table) {
            $table->id();
            $table->text('reference')->nullable();
            $table->string('ip')->nullable();
            $table->string('os')->nullable();
            $table->string('browser')->nullable();
            $table->text('visited_url')->nullable();
            $table->string('country')->nullable();
            $table->string('device' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_infos');
    }
}
