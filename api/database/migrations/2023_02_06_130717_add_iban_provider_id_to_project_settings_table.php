<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIbanProviderIdToProjectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('iban_provider_id')->nullable();
            $table->foreign('iban_provider_id')->references('id')->on('payment_provider_ibans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_settings', function (Blueprint $table) {
            $table->dropColumn('iban_provider_id');
        });
    }
}
