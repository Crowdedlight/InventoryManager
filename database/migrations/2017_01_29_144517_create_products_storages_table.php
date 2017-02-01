<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_storages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount')->default(0);
            $table->integer('sold_amount')->default(0);
            $table->string('modifiedBy');
            $table->timestamps();
            $table->integer('FK_storageID')->unsigned();
            $table->integer('FK_productID')->unsigned();
            $table->softDeletes();
        });

        Schema::table('products_storages', function ($table) {
            $table->foreign('FK_storageID')->references('id')->on('storages');
            $table->foreign('FK_productID')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_storages');
    }
}
