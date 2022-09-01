<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceListFeesIdForeignKeyInPriceListFeesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list_fees_item', function (Blueprint $table) {
            $table->dropForeign(['price_list_fees_id']);

            $table->foreign('price_list_fees_id')
                ->references('id')
                ->on('price_list_fees')
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
        Schema::table('price_list_fees_item', function (Blueprint $table) {
            $table->dropForeign(['price_list_fees_id']);

            $table->foreign('price_list_fees_id')
                ->references('id')
                ->on('price_list_fees');
        });
    }
}
