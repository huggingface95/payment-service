<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyExchangeRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('currency_from_id');
            $table->unsignedInteger('currency_to_id');
            $table->decimal('rate', 15, 5);

            $table->unique(['currency_from_id', 'currency_to_id']);
            $table->foreign('currency_from_id')->references('id')->on('currencies');
            $table->foreign('currency_to_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_exchange_rates');
    }
}
