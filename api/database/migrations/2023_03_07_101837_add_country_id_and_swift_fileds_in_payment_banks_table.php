<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdAndSwiftFiledsInPaymentBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_banks', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('swift')->nullable();

            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
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
            $table->dropForeign(['country_id']);

            $table->dropColumn(['country_id', 'swift']);
        });
    }
}
