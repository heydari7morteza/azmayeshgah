<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectProtocolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_protocol', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('entity_id')->index();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->unsignedBigInteger('source_id')->index()->nullable();
            $table->foreign('source_id')->references('id')->on('project_map')->onDelete('cascade');
            $table->unsignedBigInteger('target_id')->index()->nullable();
            $table->foreign('target_id')->references('id')->on('project_map')->onDelete('cascade');
            $table->smallInteger('sequence');

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
        Schema::dropIfExists('project_protocol');
    }
}
