<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->integer('category_order')->default(0);
            $table->string('image')->nullable()->default(NULL);
            $table->boolean('has_colors')->default(0);
            $table->boolean('has_sizes')->default(0);
            $table->boolean('has_color_no')->default(0);
            $table->enum('type', ['0', '1'])->default('0');
            $table->enum('is_active', [0,1])->default(1);
            $table->enum('is_deleted', [0,1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
