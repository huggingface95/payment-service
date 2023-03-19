<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListFeesPPTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_pp_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('type_id');
            $table->tinyInteger('operation_type_id');
            $table->integer('period_id');
            $table->integer('payment_system_id');
            $table->integer('payment_provider_id');
            $table->timestamps();

            $table->foreign('operation_type_id')->references('id')->on('operation_type');
            $table->foreign('payment_system_id')->references('id')->on('payment_system');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list_fees_p_p');
    }
}
