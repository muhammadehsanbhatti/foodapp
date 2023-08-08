<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
         	$table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('business_name', 100)->nullable();
            $table->string('restaurant_address',100)->nullable();
            $table->string('business_image')->nullable();
            $table->string('business_description',15)->nullable();
            $table->bigInteger('starting_price')->nullable();
            $table->string('ordr_delivery_time')->nullable();
            $table->enum('business_type', ['Home Kitchen', 'Restaurant'])->default('Restaurant');
            $table->enum('cuisine_type', ['None', 'Indian cuisine'])->default('None');
            $table->softDeletes();
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
        Schema::dropIfExists('businesses');
    }
}
