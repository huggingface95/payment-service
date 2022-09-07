<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ApplicantBankingAccess;
use App\Models\CommissionPriceList;
use App\Models\PriceListFee;
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

        for ($i = 0; $i < 3; $i++) {
            ApplicantBankingAccess::insert([
                'applicant_individual_id' => 1,
                'applicant_company_id' => 1,
                'member_id' => 2,
                'daily_limit' => str_pad(mt_rand(1, 9999), 5, '0', STR_PAD_LEFT),
                'monthly_limit' => str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'operation_limit' => str_pad(mt_rand(1, 99), 5, '0', STR_PAD_LEFT),
                'contact_administrator' => $faker->boolean,
                'can_sign_payment' => $faker->boolean,
                'can_create_payment' => $faker->boolean,
            ]);
        }
    }
}
