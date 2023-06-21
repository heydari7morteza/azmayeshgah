<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_map', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('device_position_id')->index();
            $table->foreign('device_position_id')->references('id')->on('device_positions')->onDelete('cascade');
            $table->unsignedBigInteger('entity_id')->index();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');

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
        Schema::dropIfExists('project_map');
    }
}
