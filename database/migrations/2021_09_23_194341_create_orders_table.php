<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->boolean("is_processed")->default(false);
            $table->boolean("returned_error")->default(false);
            $table->boolean("is_invoiced")->default(false);
            $table->string("invoice_number")->nullable();
            $table->json("original_resource");
            $table->json("processed_resource");
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
        Schema::dropIfExists('orders');
    }
}
