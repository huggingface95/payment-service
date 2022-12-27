<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use Faker\Factory as Faker;
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
        $faker = Faker::create();

        for ($i = 1; $i <= 3; $i++) {
            ApplicantCompany::insert(
                [
                    'id' => $i,
                    'name' => 'Applicant Company Test ' . $i,
                    'email' => 'applicant' . $i . '@test.com',
                    'url' => 'https://applicant-company-test' . $i . '.com',
                    'phone' => $faker->phoneNumber(),
                    'country_id' => 1,
                    'city' => $faker->city(),
                    'address' => $faker->address(),
                    'address2' => $faker->address(),
                    'office_address' => $faker->address(),
                    'zip' => $faker->postcode(),
                    'reg_at' => $faker->date(),
                    'expires_at' => $faker->date(),
                    'applicant_state_id' => 1,
                    'account_manager_member_id' => 2,
                    'company_id' => 1,
                    'owner_id' => 1,
                    'owner_relation_id' => 1,
                    'owner_position_id' => 1,
                    'applicant_state_reason_id' => 1,
                ]
            );
        }
    }
}
