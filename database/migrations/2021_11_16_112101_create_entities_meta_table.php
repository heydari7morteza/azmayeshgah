<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities_meta', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entity_id')->index();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');

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
        Schema::dropIfExists('entities_meta');
    }
}
