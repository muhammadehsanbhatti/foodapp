<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurnat_menu_id');
            $table->foreign('restaurnat_menu_id')->references('id')->on('restaurant_menues')->onUpdate('cascade')->onDelete('cascade');
            $table->string('restaurant_file',255)->nullable();
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
        Schema::dropIfExists('restaurant_files');
    }
}
