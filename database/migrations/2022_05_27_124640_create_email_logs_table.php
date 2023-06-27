<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('email', 100)->nullable();
            $table->text('email_message')->nullable();
            $table->string('email_subject', 100)->nullable();
            $table->enum('email_status', ['Pending', 'Send', 'Stop'])->default('Pending');
            $table->timestamp('send_at')->nullable();
            $table->timestamp('stop_at')->nullable();
            $table->enum('send_email_after', ['Daily', '6-Months', '6-Weeks'])->default('Daily');
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
        Schema::dropIfExists('email_logs');
    }
}