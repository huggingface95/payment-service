<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCurrencyExchangeRatesUniqueKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_exchange_rates', function (Blueprint $table) {
            $table->dropUnique(['currency_from_id', 'currency_to_id']);
            $table->unique(['currency_from_id', 'currency_to_id', 'quote_provider_id']);
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
            $table->dropUnique(['currency_from_id', 'currency_to_id', 'quote_provider_id']);
            $table->unique(['currency_from_id', 'currency_to_id']);
        });
    }
}
