<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{

    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->json('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('slug')->nullable();
            $table->longText('blog_content');
            $table->string('image');
            $table->string('author')->nullable();
            $table->string('excerpt')->nullable();
            $table->string('image_gallery')->nullable();
            $table->string('views')->nullable();
            $table->text('video_url')->nullable();
            $table->string('video_thumbnail')->nullable();
            $table->text('embed_code')->nullable();
            $table->string('order_by')->nullable();
            $table->string('visibility')->nullable();
            $table->string('featured')->nullable();
            $table->string('schedule_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
