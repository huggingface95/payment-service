<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListFeeCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_fee_currency', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_fee_id');
            $table->unsignedBigInteger('currency_id');
            $table->jsonb('fee')->nullable();

            $table->foreign('price_list_fee_id')->references('id')->on('price_list_fees')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fee_currency');
    }
}
