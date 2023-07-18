<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankCountryIdToPaymentProviderIbansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_provider_ibans', function (Blueprint $table) {
            $table->unsignedInteger('bank_country_id')->nullable();

            $table->foreign('bank_country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_provider_ibans', function (Blueprint $table) {
            $table->dropColumn('bank_country_id');
        });
    }
}
