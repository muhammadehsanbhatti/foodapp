<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vechicle_id')->nullable();
            $table->foreign('vechicle_id')->references('id')->on('rider_vechicle_information')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->enum('asset_type', ['Vechicle', 'License', 'Other'])->nullable();

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
        Schema::dropIfExists('rider_assets');
    }
}
