<?php

use App\Enums\ApplicantTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateApplicantModulesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW applicant_modules_view AS
                select * from (
                  (
                  select
                      am.applicant_individual_id as client_id,'ApplicantIndividual' as client_type,am.module_id,am.is_active,grmi.group_role_id,a.id as account_id, a.account_number
                  FROM applicant_individual_modules am
                  LEFT JOIN group_role_members_individuals grmi on grmi.user_type='ApplicantIndividual' and grmi.user_id=am.applicant_individual_id
                  left join accounts a on a.client_type='ApplicantIndividual' and a.client_id = am.applicant_individual_id
                  )
                  UNION
                  (
                  select am.applicant_company_id as clint_id,'ApplicantCompany' as client_type,am.module_id,am.is_active,grmi.group_role_id,a.id as account_id, a.account_number
                   FROM applicant_company_modules am
                   LEFT JOIN group_role_members_individuals grmi on grmi.user_type='ApplicantCompany' and grmi.user_id=am.applicant_company_id
                   left join accounts a on a.client_type='ApplicantCompany' and a.client_id = am.applicant_company_id
                   )
              ) AS applicant_modules
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_modules_view');
    }
}
