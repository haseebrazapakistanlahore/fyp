<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('title');
            $table->enum('product_type',[0,1,2])->default(0);
            $table->integer('available_quantity')->default(0);
         
            // type 0 for comsumer products
            // type 1 for professional products
            $table->integer('category_id')->unsigned()->index();
            $table->integer('sub_category_id')->nullable();
            $table->string('sub_child_category_id')->nullable()->default(NULL);
            $table->integer('min_order_level')->default(1);
            $table->integer('price')->default(0);
            $table->integer('shipping_cost')->default(0);
         
            $table->boolean('offer_available')->default(false);
            $table->integer('offer_price')->nullable();
            $table->string('size')->nullable()->default(NULL);
            $table->string('color_no')->nullable()->default(NULL);
            
            $table ->string('thumbnail')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->text('description');
            $table->timestamps();
            $table->primary('id');
        });

        Schema::table('products', function(Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
