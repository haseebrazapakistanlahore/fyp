<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('consumer_id')->nullable();
            $table->uuid('professional_id')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('country')->default('Pakistan');
            // 0 for normal address
            // 1 for shipping_address
            $table->enum('address_type',[0,1])->default(0);
            $table->timestamps();
        });

        Schema::table('addresses', function(Blueprint $table) {
            $table->foreign('consumer_id')->references('id')->on('consumers');
            $table->foreign('professional_id')->references('id')->on('professionals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
