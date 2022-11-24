<?php

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageOwnedFieldChangedApplicantIndividualIdFiledInApplicantIndividualCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual_company', function (Blueprint $table) {
            $table->dropForeign(['applicant_individual_id']);

            $table->decimal('percentage_owned', 5, 2)->nullable();
            $table->enum('applicant_type', [class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)])->default(class_basename(ApplicantIndividual::class));
            $table->dropUnique(['applicant_individual_id', 'applicant_company_id']);
            $table->renameColumn('applicant_individual_id', 'applicant_id');
            $table->unique(['applicant_id', 'applicant_type', 'applicant_company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_individual_company', function (Blueprint $table) {
            $table->dropColumn('percentage_owned');
            $table->dropUnique(['applicant_id', 'applicant_type', 'applicant_company_id']);
            $table->dropColumn('applicant_type');
            $table->renameColumn('applicant_id', 'applicant_individual_id');
            $table->unique(['applicant_individual_id', 'applicant_company_id']);

            $table->foreign('applicant_individual_id')->references('id')->on('applicant_individual');
        });
    }
}
