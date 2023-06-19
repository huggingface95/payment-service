<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReasonFieldInTransfersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->string('reason', 255)->nullable()->change();
            $table->unsignedBigInteger('respondent_fees_id')->default(1)->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->string('reason', 255)->nullable()->change();
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
            $table->string('reason', 255)->change();
            $table->unsignedBigInteger('respondent_fees_id')->change();
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->string('reason', 255)->change();
        });
    }
}
