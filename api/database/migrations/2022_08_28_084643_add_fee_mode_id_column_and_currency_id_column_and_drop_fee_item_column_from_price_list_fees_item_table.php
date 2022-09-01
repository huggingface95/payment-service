<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeModeIdColumnAndCurrencyIdColumnAndDropFeeItemColumnFromPriceListFeesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list_fees_item', function (Blueprint $table) {
            $table->dropColumn('fee_item');

            $table->unsignedBigInteger('fee_mode_id')->nullable();
            $table->integer('fee')->nullable();
            $table->integer('fee_from')->nullable();
            $table->integer('fee_to')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('fee_mode_id')->references('id')->on('fee_modes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_list_fees_item', function (Blueprint $table) {
            $table->json('fee_item')->nullable();

            $table->dropForeign('currency_id');
            $table->dropForeign('fee_mode_id');

            $table->dropColumn('fee_mode_id');
            $table->dropColumn('fee');
            $table->dropColumn('fee_from');
            $table->dropColumn('fee_to');
            $table->dropColumn('currency_id');
        });
    }
}
