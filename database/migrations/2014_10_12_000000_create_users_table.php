<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username')->unique();
            $table->integer('role_id');
            $table->string('password');
            $table->boolean('isProtected')->default(false);
            $table->boolean('canEdit')->default(true);
            $table->boolean('status')->default(true);
            $table->boolean('canLogin')->default(true);
            $table->smallInteger('failedLoginAttempts')->default(0);
            $table->smallInteger('lockoutThreshold')->default(0);
            $table->boolean('accountLocked')->default(false);
            $table->dateTime('failedLoginTime')->nullable();
            $table->dateTime('lastLogin')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
