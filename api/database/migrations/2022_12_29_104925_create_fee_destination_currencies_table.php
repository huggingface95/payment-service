<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeDestinationCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_fee_destination_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_fee_currency_id');
            $table->unsignedBigInteger('currency_id');

            $table->foreign('price_list_fee_currency_id')->references('id')->on('price_list_fee_currency')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fee_destination_currencies');
    }
}
