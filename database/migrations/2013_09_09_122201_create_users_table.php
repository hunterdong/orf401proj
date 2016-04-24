<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fbid')->nullable();
            $table->string('gid')->nullable();
            $table->string('twid')->nullable();
            $table->string('username', 255);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('fullname');
            $table->date('dob')->nullable();
            $table->string('gender')->default('male');
            $table->string('avatar')->default('user');
            $table->string('country')->nullable();
            $table->string('about_me')->nullable();
            $table->string('blogurl')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('permission')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('email_comment')->default(1);
            $table->boolean('email_reply')->default(1);
            $table->boolean('email_follow')->default(1);
            $table->boolean('email_favorite')->default(1);
            $table->string('email_confirmation')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamp('confirmed_at')->nullable();
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