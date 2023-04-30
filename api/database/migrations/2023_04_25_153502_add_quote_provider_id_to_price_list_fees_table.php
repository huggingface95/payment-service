<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteProviderIdToPriceListFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_provider_id')->nullable()->after('period_id');

            $table->foreign('quote_provider_id')->references('id')->on('quote_providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_list_fees', function (Blueprint $table) {
            $table->dropForeign(['quote_provider_id']);
            
            $table->dropColumn('quote_provider_id');
        });
    }
}
