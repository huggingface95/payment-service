<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKycLevelIdFieldInApplicantIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->unsignedBigInteger('kyc_level_id')->nullable();

            $table->foreign('kyc_level_id')->references('id')->on('applicant_kyc_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->dropColumn('kyc_level_id');
        });
    }
}
