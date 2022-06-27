<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionPriceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_price_list', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('payment_system_id');
            $table->unsignedBigInteger('commission_template_id');
            $table->foreign('provider_id')->references('id')->on('payment_provider');
            $table->foreign('payment_system_id')->references('id')->on('payment_system');
            $table->foreign('commission_template_id')->references('id')->on('commission_template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_price_list');
    }
}
