<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{

    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug')->nullable();
            $table->longText('page_content')->nullable();
            $table->string('visibility')->nullable();
            $table->string('status')->nullable();
            $table->string('sidebar_layout_two_status')->nullable();
            $table->string('sidebar_layout_two')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
