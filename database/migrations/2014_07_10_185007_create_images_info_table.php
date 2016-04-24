<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesInfoTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_info', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('image_id');
            $table->string('camera')->nullable();
            $table->string('lens')->nullable();
            $table->string('focal_length')->nullable();
            $table->string('shutter_speed')->nullable();
            $table->string('aperture')->nullable();
            $table->string('iso')->nullable();
            $table->string('license')->nullable();
            $table->double('latitude', 17, 14)->nullable();
            $table->double('longitude', 17, 14)->nullable();
            $table->timestamp('taken_at')->nullable();
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
