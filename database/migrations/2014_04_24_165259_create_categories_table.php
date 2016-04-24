<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('categories', function(Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('slug');
      $table->integer('parent_id')->nullable();
      $table->integer('lft')->nullable();
      $table->integer('rgt')->nullable();
      $table->integer('depth')->nullable();
      $table->timestamps();
      $table->index('parent_id');
      $table->index('lft');
      $table->index('rgt');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
   // Schema::drop('categories');
  }

}
