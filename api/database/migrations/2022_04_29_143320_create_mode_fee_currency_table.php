<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeFeeCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mode_fee_currency', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_mode_fee_id');
            $table->unsignedBigInteger('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mode_fee_currency');
    }
}
