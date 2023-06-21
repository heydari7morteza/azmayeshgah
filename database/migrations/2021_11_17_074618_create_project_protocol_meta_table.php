<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectProtocolMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_protocol_meta', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_protocol_id')->index();
            $table->foreign('project_protocol_id')->references('id')->on('project_protocol')->onDelete('cascade');

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
        Schema::dropIfExists('project_protocol_meta');
    }
}
