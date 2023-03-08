<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSystemMessageAndBankMessageColumnsToTextInTransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->text('system_message')->change();
            $table->text('bank_message')->change();
        });

        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->text('system_message')->change();
            $table->text('bank_message')->change();
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
            $table->string('system_message', 255)->change();
            $table->string('bank_message', 255)->change();
        });

        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->string('system_message', 255)->change();
            $table->string('bank_message', 255)->change();
        });
    }
}
