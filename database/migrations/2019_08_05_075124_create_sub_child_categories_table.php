<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubChildCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_child_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('sub_category_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->index();
            $table->string('image')->nullable()->default(NULL);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::table('sub_child_categories', function(Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });
      
        Schema::table('sub_child_categories', function(Blueprint $table) {
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_child_categories');
    }
}
