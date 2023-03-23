<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentBankCurrenciesRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_bank_currencies_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_bank_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('region_id');

            $table->unique(['payment_bank_id', 'currency_id', 'region_id']);

            $table->foreign('payment_bank_id')->references('id')->on('payment_banks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_bank_currencies_regions');
    }
}
