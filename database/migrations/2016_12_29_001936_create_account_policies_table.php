<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('role_id')->unique();
            $table->smallInteger('failedLoginAttempts');
            $table->smallInteger('lockoutDuration');
            $table->smallInteger('threshold');
            $table->smallInteger('reset');
            $table->integer('createdBy');
            $table->integer('updatedBy');
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
        Schema::drop('account_policies');
    }
}
