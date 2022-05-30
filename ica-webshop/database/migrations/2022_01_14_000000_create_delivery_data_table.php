<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_data', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_method')->nullable();
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('delivery_cost')->nullable();
            $table->unsignedBigInteger('payment_cost')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email');
            $table->unsignedBigInteger('delivery_address_id')->unique()->nullable();
            $table->unsignedBigInteger('billing_address_id')->unique()->nullable();
            $table->timestamps();

            $table->foreign('delivery_address_id')->references('id')->on('addresses')->nullOnDelete();
            $table->foreign('billing_address_id')->references('id')->on('addresses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_data');
    }
}
