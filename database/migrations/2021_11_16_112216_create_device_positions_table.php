<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_positions', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('device_id')->index();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->unsignedBigInteger('position_id')->index();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->json('point_a')->nullable();
            $table->json('point_b')->nullable();
            $table->integer('length')->nullable();
            $table->integer('width')->nullable();



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
        Schema::dropIfExists('device_positions');
    }
}
