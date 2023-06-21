<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicePositionsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_positions_meta', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('device_position_id')->index();
            $table->foreign('device_position_id')->references('id')->on('device_positions')->onDelete('cascade');

            $table->string('type')->default('null');

            $table->string('key')->index();
            $table->text('value')->nullable();
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
        Schema::dropIfExists('device_positions_meta');
    }
}
