<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBeneficiaryFieldsToTransferIncomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedInteger('beneficiary_type_id')->nullable()->change();
            $table->string('beneficiary_name', 255)->nullable()->change();
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
            $table->unsignedInteger('beneficiary_type_id')->change();
            $table->string('beneficiary_name', 255)->change();
        });
    }
}
