<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stores', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->foreignId('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string("stock")->nullable();
            $table->string("sale_price")->nullable();
            $table->string("regular_price")->nullable();
            $table->enum('status', ['to_process', 'failed', 'success'])->default('to_process');
            $table->text('error')->nullable();
            $table->timestamps();
            $table->foreign('sku')->references('sku')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stores');
    }
}
