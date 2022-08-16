<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorDataTable extends Migration
{

    public function up()
    {
        Schema::create('author_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogable_id');
            $table->string('blogable_type');
            $table->unsignedBigInteger('blog_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('author_data');
    }
}
