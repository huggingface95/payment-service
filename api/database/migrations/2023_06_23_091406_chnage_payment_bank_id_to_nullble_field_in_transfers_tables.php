<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChnagePaymentBankIdToNullbleFieldInTransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_bank_id')->nullable()->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_bank_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_bank_id')->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_bank_id')->change();
        });
    }
}
