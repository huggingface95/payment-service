<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueNameAndCompanyIdInApplicantIndividualCompanyRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_individual_company_relation', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_individual_company_relation', function (Blueprint $table) {
            $table->dropUnique(['applicant_individual_company_relation_name_company_id_unique']);
            $table->unique(['name']);
        });
    }
}
