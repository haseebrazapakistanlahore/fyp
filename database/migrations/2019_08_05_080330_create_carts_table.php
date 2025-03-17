<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->bigInteger('net_total');
            $table->bigInteger('gross_total');
            $table->string('coupon_code')->nullable()->default(NULL);
            $table->bigInteger('coupon_discount_amount')->default(0);
            $table->integer('discount_id')->nullable()->default(NULL);
            $table->bigInteger('discount_amount')->default(0);
            $table->timestamps();
        });
        Schema::table('carts', function(Blueprint $table) {
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
        Schema::dropIfExists('carts');
    }
}
