<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnVideoDurationToBlogsTable extends Migration
{

    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('video_duration')->nullable();
        });
    }


    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('video_duration');
        });
    }
}
