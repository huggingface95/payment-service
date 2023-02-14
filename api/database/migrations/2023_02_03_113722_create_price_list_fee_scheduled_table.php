<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListFeeScheduledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_fee_scheduled', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_fee_id');
            $table->dateTime('starting_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('executed_date')->nullable();
            $table->unsignedInteger('recurrent_interval')->nullable();
            $table->unsignedBigInteger('starting_date_id')->nullable();
            $table->unsignedBigInteger('end_date_id')->nullable();

            $table->foreign('price_list_fee_id')->references('id')->on('price_list_fees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fee_scheduled');
    }
}
