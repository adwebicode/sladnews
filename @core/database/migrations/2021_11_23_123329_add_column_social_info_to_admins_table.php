<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSocialInfoToAdminsTable extends Migration
{

    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
        });
    }


    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('facebook_url');
            $table->dropColumn('twitter_url');
            $table->dropColumn('instagram_url');
            $table->dropColumn('linkedin_url');
        });
    }
}
