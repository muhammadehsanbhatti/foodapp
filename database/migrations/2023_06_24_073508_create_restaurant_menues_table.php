<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantMenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_menues', function (Blueprint $table) {
            $table->id();
          	$table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('businesses')->onUpdate('cascade')->onDelete('cascade');
            $table->string('item_name', 100)->nullable();
            $table->longText('description')->nullable();
            $table->integer('regular_price')->nullable();
            $table->integer('sale_price')->nullable();
            $table->string('stock')->nullable();
            $table->string('sku')->nullable();
            $table->enum('category', ['Childrens menu', 'Wine Menu','Beverage menu','Vegetarian Menu'])->nullable();
            $table->enum('category_type', ['Childrens menu', 'Wine Menu','Beverage menu','Vegetarian Menu'])->nullable();
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
        Schema::dropIfExists('restaurant_menues');
    }
}
