<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferBetweenRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_between_relation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_outgoing_id');
            $table->unsignedBigInteger('transfer_incoming_id');

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
        Schema::dropIfExists('transfer_between_relation');
    }
}
