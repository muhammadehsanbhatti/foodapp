<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderVechicleInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_vechicle_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('company')->nullable();
            $table->string('color')->nullable();
            $table->string('model')->nullable();
            $table->string('vechicle_number')->nullable();
            $table->enum('vechicle_condition', ['New', 'Normal', 'Rough'])->nullable();
            $table->enum('vechicle_type', ['Car', 'Bike', 'ApplePay', 'CashOnDelivery'])->nullable();
            
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
        Schema::dropIfExists('rider_vechicle_information');
    }
}
