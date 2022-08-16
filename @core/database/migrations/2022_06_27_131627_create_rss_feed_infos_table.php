<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRssFeedInfosTable extends Migration
{
    public function up()
    {
        Schema::create('rss_feed_infos', function (Blueprint $table) {
            $table->id();
            $table->longText('link')->nullable();
            $table->string('automation_type')->nullable();
            $table->integer('imported_item')->nullable();
            $table->string('status')->nullable();
            $table->string('last_import_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rss_feed_infos');
    }
}
