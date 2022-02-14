<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantIndividualCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_individual_company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_individual_id');
            $table->unsignedBigInteger('applicant_company_id');
            $table->unsignedBigInteger('applicant_individual_company_relation_id');
            $table->unsignedBigInteger('applicant_individual_company_position_id');
            $table->unique(['applicant_individual_id','applicant_company_id']);
            $table->foreign('applicant_individual_id')->references('id')->on('applicant_individual');
            $table->foreign('applicant_company_id')->references('id')->on('applicant_companies');
            $table->foreign('applicant_individual_company_relation_id')->references('id')->on('applicant_individual_company_relation');
            $table->foreign('applicant_individual_company_position_id')->references('id')->on('applicant_individual_company_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_individual_company');
    }
}
