<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantCompanyModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_company_modules', function (Blueprint $table) {
            $table->unsignedBigInteger('applicant_company_id');
            $table->unsignedBigInteger('applicant_module_id');
            $table->unique(['applicant_company_id', 'applicant_module_id']);
            $table->foreign('applicant_company_id')->references('id')->on('applicant_companies')->onDelete('cascade');
            $table->foreign('applicant_module_id')->references('id')->on('applicant_modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_company_modules');
    }
}
