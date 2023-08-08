<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenueVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menue_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_menue_id');
            $table->foreign('restaurant_menue_id')->references('id')->on('restaurant_menues')->onUpdate('cascade')->onDelete('cascade');
            $table->string('variant_name')->nullable();
            $table->string('variant_price')->nullable();
            $table->string('variant_image')->nullable();
            $table->enum('menue_type', ['Deal', 'Simple','Special'])->default('Simple');
            $table->enum('variant_type', ['Required', 'Optional'])->nullable();
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
        Schema::dropIfExists('menue_variants');
    }
}
