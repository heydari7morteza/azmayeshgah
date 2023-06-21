<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->index();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->string('name',255);
            $table->text('description')->nullable();
            $table->integer('estimate_tips')->default(0);
            $table->integer('estimate_time')->default(0);
            $table->text('gcode')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
