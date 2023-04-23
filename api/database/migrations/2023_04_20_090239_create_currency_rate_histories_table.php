<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyRateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rate_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_provider_id');
            $table->unsignedBigInteger('currency_src_id');
            $table->unsignedBigInteger('currency_dst_id');
            $table->decimal('rate', 15, 5);
            $table->dateTime('created_at');

            $table->foreign('quote_provider_id')->references('id')->on('quote_providers');
            $table->foreign('currency_src_id')->references('id')->on('currencies');
            $table->foreign('currency_dst_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_rate_histories');
    }
}
