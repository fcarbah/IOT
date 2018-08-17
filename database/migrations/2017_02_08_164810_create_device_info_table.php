<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('device_info', function (Blueprint $table) {
          $table->increments('id');
          $table->text('camera');
          $table->text('location');
          $table->text('driver');
          $table->text('owner_info');
          $table->text('temp_info');
          $table->text('notif_info');
          $table->tinyInteger('setup_step')->default(0);
          $table->boolean('setup_complete')->default(false);
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
        Schema::drop('device_info');
    }
}
