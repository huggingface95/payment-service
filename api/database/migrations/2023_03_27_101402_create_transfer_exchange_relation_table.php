<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferExchangeRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_exchanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('requested_by_id');
            $table->unsignedBigInteger('debited_account_id');
            $table->unsignedBigInteger('credited_account_id');
            $table->unsignedSmallInteger('status_id');
            $table->unsignedBigInteger('transfer_outgoing_id');
            $table->unsignedBigInteger('transfer_incoming_id');
            $table->decimal('exchange_rate', 15, 5);
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('payment_status');
            $table->foreign('transfer_outgoing_id')->references('id')->on('transfer_outgoings')->onDelete('cascade');
            $table->foreign('transfer_incoming_id')->references('id')->on('transfer_incomings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_exchanges');
    }
}
