<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('consumer_id')->nullable();
            $table->uuid('professional_id')->nullable();
            $table->bigInteger('net_total');
            $table->bigInteger('gross_total');
            $table->string('coupon_code')->nullable()->default(NULL);
            $table->bigInteger('coupon_discount_amount')->default(0);
            $table->integer('discount_id')->nullable()->default(NULL);
            $table->bigInteger('discount_amount')->default(0);
            $table->integer('address_id')->unsigned()->index();
            $table->string('tracking_id')->nullable();
            $table->enum('order_status',['Pending', 'Confirmed', 'Canceled', 'In Progress', 'Delivered', 'Completed'])->default('Pending');
            $table->enum('payment_method',['Cash On Delivery','Bank Transfer'])->default('Cash On Delivery');
            $table->enum('payment_status', ['Pending', 'Paid', 'Completed'])->default('Pending');
            $table->date('order_date');
            $table->timestamps();
            $table->primary('id');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->foreign('consumer_id')->references('id')->on('consumers');
            $table->foreign('professional_id')->references('id')->on('professionals');
            $table->foreign('address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
