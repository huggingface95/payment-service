<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferSwiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_swifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id');
            $table->enum('transfer_type', [class_basename(TransferIncoming::class), class_basename(TransferOutgoing::class)]);
            $table->string('swift')->comment('SWIFT/BIC')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_address')->nullable();
            $table->integer('bank_country_id')->nullable();
            $table->string('location')->nullable();
            $table->string('ncs_number')->nullable();
            $table->string('aba')->comment('ABA/RTN')->nullable();
            $table->string('account_number')->comment('Account/IBAN')->nullable();

            $table->foreign('bank_country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_intermediary_banks');
    }
}
