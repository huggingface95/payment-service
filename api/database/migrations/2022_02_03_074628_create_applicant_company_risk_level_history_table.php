<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantCompanyRiskLevelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_company_risk_level_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('risk_level_id');
            $table->string('comment', 255);
            $table->unsignedBigInteger('applicant_company_id');
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();
            $table->foreign('risk_level_id')->references('id')->on('applicant_risk_level');
            $table->foreign('applicant_company_id')->references('id')->on('applicant_companies');
            $table->foreign('manager_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_company_risk_level_history');
    }
}
