<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password')->nullable();
            $table->enum('user_login_status', ['super-admin', 'admin', 'customer'])->default('customer');
            $table->enum('user_status', ['Active', 'Block'])->default('Active');
            $table->enum('register_from', ['Web', 'Facebook', 'Gmail', 'Apple'])->default('Web');
            $table->string('profile_image')->nullable();
            $table->string('email_verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('theme_mode', ['Light', 'Dark'])->default('Light');
            $table->double('time_spent')->default(0);
            $table->timestamp('last_seen')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}