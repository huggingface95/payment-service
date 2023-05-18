<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransfersOutgoingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_outgoings', function (Blueprint $table) {
            $table->string('recipient_bank_location',255)->nullable();
            $table->string('recipient_bank_ncs_number',255)->nullable();
            $table->string('recipient_bank_rtn',50)->nullable();
            $table->unsignedInteger('beneficiary_type_id')->default(1);
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
            $table->dropColumn('recipient_bank_location');
            $table->dropColumn('recipient_bank_ncs_number');
            $table->dropColumn('recipient_bank_rtn');
            $table->dropColumn('beneficiary_type_id');
        });
    }
}
