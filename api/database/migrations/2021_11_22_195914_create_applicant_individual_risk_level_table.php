<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantIndividualRiskLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_individual_risk_level', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_individual_id');
            $table->unsignedBigInteger('member_id');
            $table->text('description');
            $table->timestamps();
            $table->foreign('applicant_individual_id')->references('id')->on('applicant_individual')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_individual_risk_level');
    }
}
