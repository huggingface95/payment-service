<?php

namespace Database\Seeders;

use App\Models\ApplicantIndividual;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ApplicantIndividualTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicants = [
            1 => [
                'first_name' => 'Applicant_test',
                'last_name' => 'Test',
                'url' => 'https://applicant.com',
                'phone' => '+000000000000',
                'password_hash' => Hash::make('1234567Qa'),
                'password_salt' => '4561654sd654f65d4f',
                'company_id' => 1,
                'country_id' => 1,
                'birth_country_id' => 1,
                'birth_at' => '1991-08-09',
                'city' => 'New York',
                'address' => '1st street',
                'sex' => 1,
                'applicant_state_id' => 1,
                'account_manager_member_id' => 2,
                'applicant_risk_level_id' => 1,
                'applicant_state_reason_id' => 1,
                'applicant_status_id' => 1,
                'kyc_level_id' => 1,
                'project_id' => 1,
            ],
            [
                'first_name' => 'Applicant_test2',
                'last_name' => 'Test2',
                'url' => 'https://applicant2.com',
                'phone' => '+000000000000',
                'password_hash' => Hash::make('1234567Qa'),
                'password_salt' => '4561654sd654f65d4f',
                'company_id' => 1,
                'country_id' => 1,
                'birth_country_id' => 1,
                'birth_at' => '1991-08-09',
                'city' => 'New York',
                'address' => '1st street',
                'sex' => 1,
                'applicant_state_id' => 1,
                'account_manager_member_id' => 2,
                'applicant_risk_level_id' => 1,
                'applicant_state_reason_id' => 1,
                'applicant_status_id' => 1,
                'kyc_level_id' => 1,
                'project_id' => 1,
            ],
        ];

        foreach ($applicants as $id => $applicant) {
            ApplicantIndividual::firstOrCreate([
                'id' => $id,
                'email' => 'applicant'.$id.'@test.com',
            ], $applicant);
        }
    }
}
