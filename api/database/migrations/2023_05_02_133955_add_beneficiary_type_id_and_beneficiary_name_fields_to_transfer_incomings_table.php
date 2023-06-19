<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeneficiaryTypeIdAndBeneficiaryNameFieldsToTransferIncomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedInteger('beneficiary_type_id')->default(1);
            $table->string('beneficiary_name', 255)->default('Test Name');
        });

        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedInteger('beneficiary_type_id')->default(null)->change();
            $table->string('beneficiary_name', 255)->default(null)->change();
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
            $table->dropColumn('beneficiary_type_id');
            $table->dropColumn('beneficiary_name');
        });
    }
}
