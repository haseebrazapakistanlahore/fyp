<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('company_name')->nullable()->default(NULL);
            $table->string('company_website')->nullable()->default(NULL);
            $table->string('company_address')->nullable()->default(NULL);
            $table->boolean('is_authorized')->default(false);
            $table->boolean('email_sent')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->dateTime('last_login')->nullable();
            $table->boolean('discount_allowed')->default(false);
            $table->bigInteger('min_order_value')->nullable()->default(0);
            $table->integer('discount_value')->nullable()->default(0);
            $table->timestamps();
            $table->rememberToken();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professionals');
    }
}
