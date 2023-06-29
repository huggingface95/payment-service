<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencySrcIdAndCurrencyDstIdToCurrencyExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_exchange_rates', function (Blueprint $table) {
            $table->dropColumn(['currency_from_id', 'currency_to_id']);

            $table->unsignedBigInteger('currency_src_id')->nullable();
            $table->unsignedBigInteger('currency_dst_id')->nullable();

            $table->unique(['quote_provider_id', 'currency_src_id', 'currency_dst_id']);
            $table->foreign('currency_src_id')->references('id')->on('currencies');
            $table->foreign('currency_dst_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency_exchange_rates', function (Blueprint $table) {
            $table->dropColumn(['currency_src_id', 'currency_dst_id']);

            $table->unsignedBigInteger('currency_from_id')->nullable();
            $table->unsignedBigInteger('currency_to_id')->nullable();
        });
    }
}
