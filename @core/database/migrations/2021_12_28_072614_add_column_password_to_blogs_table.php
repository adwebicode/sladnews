<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPasswordToBlogsTable extends Migration
{

    public function up()
    {

        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs','password')) {
                $table->string('password',191)->nullable();
            }
        });

    }


    public function down()
    {

        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs','password')){
              $table->dropColumn('password');
            }
        });

     }



}