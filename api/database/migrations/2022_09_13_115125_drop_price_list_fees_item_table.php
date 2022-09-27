<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPriceListFeesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('price_list_fees_item');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('price_list_fees_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_fees_id');
            $table->unsignedBigInteger('fee_mode_id')->nullable();
            $table->integer('fee')->nullable();
            $table->integer('fee_from')->nullable();
            $table->integer('fee_to')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('fee_mode_id')->references('id')->on('fee_modes');
            $table->foreign('price_list_fees_id')
                ->references('id')
                ->on('price_list_fees')
                ->onDelete('cascade');
        });
    }
}
