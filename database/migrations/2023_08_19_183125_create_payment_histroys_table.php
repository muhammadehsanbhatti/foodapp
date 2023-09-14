<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistroysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histroys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
             $table->unsignedBigInteger('payment_card_information_id');
            $table->foreign('payment_card_information_id')->references('id')->on('payment_cart_information')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('user_address_id');
            $table->foreign('user_address_id')->references('id')->on('user_addresses')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->foreign('rider_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->string('amount_captured')->nullable();
            $table->string('currency')->nullable();
            $table->integer('item_delivered_quantity')->nullable();
            $table->integer('rider_charges')->nullable();
            $table->enum('payment_status', ['Stripe', 'Paypal', 'ApplePay', 'CashOnDelivery'])->nullable();
            $table->enum('order_status', ['Pending','Preparing', 'InProgress','Late','Rejected','Ready To Deliver','Delivered'])->default('Pending');
            $table->enum('delivery_status', ['Accept', 'Pending', 'Preparing', 'OnWay', 'Delivered'])->default('Pending');
            $table->string('payment_sku')->nullable();
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
        Schema::dropIfExists('payment_histroys');
    }
}
