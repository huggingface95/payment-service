<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantIndividualRiskLevelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_individual_risk_level_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('risk_level_id');
            $table->string('comment', 255);
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();
            $table->foreign('risk_level_id')->references('id')->on('applicant_risk_level');
            $table->foreign('applicant_id')->references('id')->on('applicant_individual');
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
        Schema::dropIfExists('applicant_individual_risk_level_history');
    }
}
