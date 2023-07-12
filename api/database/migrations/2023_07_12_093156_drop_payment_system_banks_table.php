<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPaymentSystemBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payment_system_banks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_system_banks', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_system_id');
            $table->unsignedBigInteger('payment_bank_id');
            $table->foreign('payment_system_id')->references('id')->on('payment_system')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_bank_id')->references('id')->on('payment_banks')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
