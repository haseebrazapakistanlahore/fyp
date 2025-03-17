<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->double('rating', 2, 1);
            $table->text('comment')->nullable();
            $table->uuid('product_id');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
        
        Schema::table('reviews', function(Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });
        
        Schema::table('reviews', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
