<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantIndividualModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_individual_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_individual_id');
            $table->unsignedBigInteger('applicant_module_id');
            $table->foreign('applicant_individual_id')->references('id')->on('applicant_individual');
            $table->foreign('applicant_module_id')->references('id')->on('applicant_modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_individual_modules');
    }
}
