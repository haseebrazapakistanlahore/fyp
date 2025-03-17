<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id')->unsigned()->index();
            $table->uuid('product_id');
            $table->integer('unit_price');
            $table->integer('quantity')->default(1);
            $table->integer('product_color_id')->nullable()->default(NULL);
            $table->timestamps();
        });
        Schema::table('cart_items', function(Blueprint $table) {
            $table->foreign('cart_id')->references('id')->on('carts');
        });
        
        Schema::table('cart_items', function(Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
