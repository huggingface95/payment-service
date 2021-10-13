<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentProviderPaymentSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_provider_payment_system', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('payment_system_id');
            $table->unique(['payment_provider_id','payment_system_id']);
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider')->onDelete('cascade');
            $table->foreign('payment_system_id')->references('id')->on('payment_system');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_provider_payment_system');
    }
}
