<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use Illuminate\Database\Seeder;

class ApplicantIndividualCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicantIndividual = ApplicantIndividual::where('id', 1)->first();
        $applicantCompany = ApplicantCompany::where('id', 1)->first();

        ApplicantIndividualCompany::firstOrCreate(
            [
                'applicant_id' => $applicantIndividual->id,
                'applicant_type' => class_basename(ApplicantIndividual::class),
                'applicant_company_id' => $applicantCompany->id,
            ],
            [
                'applicant_individual_company_relation_id' => 1,
                'applicant_individual_company_position_id' => 1,
            ]
        );

        $applicantIndividual = ApplicantIndividual::where('id', 2)->first();
        $applicantCompany = ApplicantCompany::where('id', 2)->first();

        ApplicantIndividualCompany::firstOrCreate(
            [
                'applicant_id' => $applicantIndividual->id,
                'applicant_type' => class_basename(ApplicantIndividual::class),
                'applicant_company_id' => $applicantCompany->id,
            ],
            [
                'applicant_individual_company_relation_id' => 1,
                'applicant_individual_company_position_id' => 1,
            ]
        );
    }
}
