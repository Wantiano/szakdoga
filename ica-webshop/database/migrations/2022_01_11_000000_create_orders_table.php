<?php

use App\Enums\StatusEnum;
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
            $table->string('public_id')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_message')->nullable();
            $table->enum('status', StatusEnum::valueArray());
            $table->unsignedBigInteger('delivery_data_id')->unique();
            $table->timestamp('order_proccessed_at')->nullable();
            $table->unsignedBigInteger('order_managed_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('delivery_data_id')->references('id')->on('delivery_data')->onDelete('cascade');
            $table->foreign('order_managed_by')->references('id')->on('users');
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
