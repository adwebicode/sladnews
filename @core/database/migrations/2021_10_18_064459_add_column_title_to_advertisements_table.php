<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTitleToAdvertisementsTable extends Migration
{

    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
    }

    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}
