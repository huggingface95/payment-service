<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmailUniqueToApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual', function (Blueprint $table) {
            $table->dropUnique('applicant_individual_email_unique');
            $table->unique(['company_id', 'project_id', 'email']);
        });
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropUnique('applicant_companies_email_unique');
            $table->unique(['company_id', 'project_id', 'email']);
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
            $table->dropUnique('applicant_individual_company_id_project_id_email_unique');
        });
        Schema::table('applicant_companies', function (Blueprint $table) {
            $table->dropUnique('applicant_companies_company_id_project_id_email_unique');
        });

    }
}
