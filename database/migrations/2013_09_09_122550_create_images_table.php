<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('image_name');
            $table->string('title')->nullable();
            $table->string('slug');
            $table->text('image_description')->nullable();
            $table->integer('category_id')->default(1)->unsigned();
            $table->string('tags')->nullable();
            $table->string('type');
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->boolean('allow_download')->default(1);
            $table->boolean('is_adult')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('featured_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}