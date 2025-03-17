<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('order_id');
            $table->uuid('product_id');
            $table->integer('unit_price');
            $table->integer('quantity')->default(1);
            $table->integer('product_color_id')->nullable()->default(NULL);
            $table->integer('discount')->default(0);
            $table->integer('sub_total');
            $table->timestamps();
        });
        
        Schema::table('order_details', function(Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders');
        });
        
        Schema::table('order_details', function(Blueprint $table) {
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
        Schema::dropIfExists('order_details');
    }
}
