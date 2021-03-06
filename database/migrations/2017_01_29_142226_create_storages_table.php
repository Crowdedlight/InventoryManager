<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('createdBy');
            $table->boolean('depot')->default(false);
            $table->integer('FK_eventID')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
        });

        Schema::table('storages', function ($table) {
            $table->foreign('FK_eventID')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storages');
    }
}
