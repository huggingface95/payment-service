<?php

namespace Database\Seeders;

use App\Models\ApplicantBankingAccess;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ApplicantBankingAccessTableSeeder extends Seeder
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
            ApplicantBankingAccess::firstOrCreate(
                [
                    'applicant_individual_id' => 1,
                    'applicant_company_id' => 1,
                    'member_id' => $i,
                ],
                [
                    'daily_limit' => str_pad(mt_rand(1, 9999), 5, '0', STR_PAD_LEFT),
                    'monthly_limit' => str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'operation_limit' => str_pad(mt_rand(1, 99), 5, '0', STR_PAD_LEFT),
                    'contact_administrator' => $faker->boolean,
                    'role_id' => 1
                ]
            );
        }
    }
}
