<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicantCompanyRiskLevelIdToApplicantCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('applicant_company_risk_level_id')->nullable();
            $table->foreign('applicant_company_risk_level_id')->references('id')->on('applicant_risk_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropColumn('applicant_company_risk_level_id');
        });
    }
}
