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
            $table->enum('user_login_status', ['super-admin', 'admin', 'customer', 'rider'])->default('customer');
            $table->enum('register_from', ['Web', 'Facebook', 'Gmail', 'Apple'])->default('Web');
            $table->enum('user_status', ['Pending', 'InProgress', 'Approved', 'Rejected'])->default('Pending');
            $table->string('driving_license')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('certificate_img')->nullable();
            $table->string('dob')->nullable();
            $table->string('age')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->integer('loggedin_count')->default('0');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1=active, 0=deactive'); //->change();
            $table->timestamp('last_login')->nullable();
            $table->integer('is_login')->default('0');
            $table->datetime('last_activity')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->tinyInteger('is_blocked')->default('0')->comment('1=Yes, 0=No');
            $table->string('email_verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('theme_mode', ['Light', 'Dark'])->default('Light');
            $table->double('time_spent')->default(0);
            $table->longText('verification_token')->nullable();
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