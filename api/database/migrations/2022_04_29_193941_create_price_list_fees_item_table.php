<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListFeesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_fees_item', function (Blueprint $table) {
            $table->id();
            $table->json('fee_item')->nullable();
            $table->unsignedBigInteger('price_list_fees_id');
            $table->foreign('price_list_fees_id')->references('id')->on('price_list_fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fees_item');
    }
}
