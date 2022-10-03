<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentProviderIdToPaymentSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_system', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_provider_id')->nullable();
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->dropUnique(['name']);
            $table->unique(['name', 'payment_provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_system', function (Blueprint $table) {
            $table->dropUnique(['name', 'payment_provider_id']);
            $table->unique('name');
            $table->dropForeign(['payment_provider_id']);
            $table->dropColumn('payment_provider_id');
        });
    }
}
