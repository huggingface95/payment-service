<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteApplicantCompanyIdToApplicantBankingAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_banking_access', function (Blueprint $table) {
            $table->dropForeign(['applicant_company_id']);

            $table->foreign('applicant_company_id')
                ->references('id')
                ->on('applicant_companies')
                ->onDelete('cascade')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_banking_access', function (Blueprint $table) {
            $table->dropForeign(['applicant_company_id']);

            $table->foreign('applicant_company_id')
                ->references('id')
                ->on('applicant_companies');
        });
    }
}
