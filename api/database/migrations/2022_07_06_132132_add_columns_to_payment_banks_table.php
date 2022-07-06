<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPaymentBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('payment_system_id');

            $table->unique('payment_provider_id', 'payment_system_id', 'name');

            $table->foreign('payment_provider_id')->references('id')->on('payment_provider')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_system_id')->references('id')->on('payment_system')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->dropColumn(['payment_provider_id', 'payment_system_id']);
        });
    }
}
