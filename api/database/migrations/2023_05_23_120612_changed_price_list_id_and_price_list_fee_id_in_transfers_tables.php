<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangedPriceListIdAndPriceListFeeIdInTransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id')->nullable()->change();
            $table->unsignedBigInteger('price_list_fee_id')->nullable()->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id')->nullable()->change();
            $table->unsignedBigInteger('price_list_fee_id')->nullable()->change();
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_fee_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id')->nullable(false)->change();
            $table->unsignedBigInteger('price_list_fee_id')->nullable(false)->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id')->nullable(false)->change();
            $table->unsignedBigInteger('price_list_fee_id')->nullable(false)->change();
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_fee_id')->nullable(false)->change();
        });
    }
}
