<?php

namespace Database\Seeders;

use App\Models\AccountReachedLimit;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AccountReachedLimitsTableSeeder extends Seeder
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
            AccountReachedLimit::firstOrCreate(
                [
                    'account_id' => $i,
                ],
                [
                    'group_type' => 'Member',
                    'client_type' => 'Private',
                    'client_name' => $faker->name,
                    'transfer_direction' => 'Incoming',
                    'limit_type' => 'Single Transaction Amount',
                    'limit_value' => 500,
                    'limit_currency' => 'USD',
                    'period' => 5,
                    'amount' => 1000000,
                    'expires_at' => $faker->dateTime,
                ]
            );
        }
    }
}
