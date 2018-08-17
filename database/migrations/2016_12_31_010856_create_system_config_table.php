<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_config', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('wireless')->nullable();
            $table->longText('network')->nullable();
            $table->longText('notification')->nullable();
            $table->longText('security')->nullable();
            $table->longText('temperature')->nullable();
            $table->longText('defTemperature')->nullable();
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
        Schema::drop('system_config');
    }
}
