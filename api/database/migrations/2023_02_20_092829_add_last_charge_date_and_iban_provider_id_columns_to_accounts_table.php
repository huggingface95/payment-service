<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastChargeDateAndIbanProviderIdColumnsToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dateTime('last_charge_at')->nullable();
            $table->unsignedBigInteger('iban_provider_id')->nullable();

            $table->foreign('iban_provider_id')->references('id')->on('payment_provider_ibans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('last_charge_at');
            $table->dropColumn('iban_provider_id');
        });
    }
}
