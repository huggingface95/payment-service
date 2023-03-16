<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSystemRespondentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_system_respondent_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_system_id');
            $table->unsignedBigInteger('respondent_fee_id');

            $table->foreign('payment_system_id')->references('id')->on('payment_system')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('respondent_fee_id')->references('id')->on('respondent_fees')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_system_respondent_fees');
    }
}
