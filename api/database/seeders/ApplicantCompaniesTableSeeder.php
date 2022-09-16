<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use Illuminate\Database\Seeder;

class ApplicantCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantCompany::insert([
            'id' => 1,
            'name' => 'Applicant_Company_test',
            'email' => 'applicant@test.com',
            'url' => 'applicant_company_test.com',
            'phone' => '+000000000000',
            'country_id' => 1,
            'city' => 'New York',
            'address' => '1st street',
            'expires_at' => '1991-08-09',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
            'company_id' => 1,
            'owner_id' => 1,
            'owner_relation_id' => 1,
            'owner_position_id' => 1,
            'applicant_state_reason_id' => 1,
        ]);
    }
}
