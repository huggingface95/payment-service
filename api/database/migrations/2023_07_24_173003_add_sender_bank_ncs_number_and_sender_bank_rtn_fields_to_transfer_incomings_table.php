<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSenderBankNcsNumberAndSenderBankRtnFieldsToTransferIncomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->string('sender_bank_ncs_number', 255)->nullable();
            $table->string('sender_bank_rtn', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->dropColumn('sender_bank_ncs_number');
            $table->dropColumn('sender_bank_rtn');
        });
    }
}
