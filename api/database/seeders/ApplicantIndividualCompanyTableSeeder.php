<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicantIndividualCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicantIndividual = ApplicantIndividual::select('*')->where('id', 1)->get();
        $applicantCompany = ApplicantCompany::select('*')->where('id', 1)->get();
        DB::table('applicant_individual_company')->insert(['applicant_individual_id' => $applicantIndividual[0]->id, 'applicant_company_id' => $applicantCompany[0]->id, 'applicant_individual_company_relation_id' => 1, 'applicant_individual_company_position_id' => 1]);
    }
}
