<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWirelessNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wireless_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ssid');
            $table->string('password');
            $table->string('authType');
            $table->boolean('autoConnect')->default(true);
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
        Schema::drop('wireless_networks');
    }
}
