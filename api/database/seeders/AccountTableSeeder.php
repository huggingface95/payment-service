<?php

namespace Database\Seeders;

use App\Models\Account;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AccountTableSeeder extends Seeder
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
            Account::firstOrCreate(
                [
                    'account_name' => 'Test account '.$i,
                ],
                [
                    'company_id' => 1,
                    'currency_id' => 1,
                    'owner_id' => 1,
                    'account_number' => '2566'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'group_type_id' => 2,
                    'group_role_id' => 1,
                    'commission_template_id' => 1,
                    'payment_system_id' => 1,
                    'payment_provider_id' => 1,
                    'account_state_id' => 1,
                    'payment_bank_id' => 1,
                    'is_primary' => true,
                    'current_balance' => 10000,
                    'reserved_balance' => 5000,
                    'available_balance' => 10000,
                    'min_limit_balance' => 500,
                    'max_limit_balance' => 80000,
                    'account_type' => 'Business',
                    'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                    'updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                ]
            );
        }

        Account::firstOrCreate(
            [
                'account_name' => 'Test account 4',
            ],
            [
                'company_id' => 1,
                'currency_id' => 2,
                'owner_id' => 1,
                'account_number' => '2566'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'group_type_id' => 2,
                'group_role_id' => 1,
                'commission_template_id' => 1,
                'payment_system_id' => 1,
                'payment_provider_id' => 1,
                'account_state_id' => 1,
                'payment_bank_id' => 1,
                'is_primary' => true,
                'current_balance' => 10000,
                'reserved_balance' => 5000,
                'available_balance' => 10000,
                'min_limit_balance' => 500,
                'max_limit_balance' => 80000,
                'account_type' => 'Business',
                'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
            ]
        );
    }
}
