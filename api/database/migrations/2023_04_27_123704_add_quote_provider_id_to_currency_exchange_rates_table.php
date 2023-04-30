<?php

use App\Models\CurrencyExchangeRate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteProviderIdToCurrencyExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CurrencyExchangeRate::truncate();
        Schema::table('currency_exchange_rates', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_provider_id');

            $table->foreign('quote_provider_id')
                ->references('id')
                ->on('quote_providers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
            $table->dropForeign(['quote_provider_id']);
            $table->dropColumn('quote_provider_id');
        });
    }
}
