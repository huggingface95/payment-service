<?php

namespace Database\Seeders;

use App\Models\BankCorrespondent;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BankCorrespondentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            BankCorrespondent::firstOrCreate(
                [
                    'id' => $i,
                ],
                [
                    'name' => 'Bank Correspondent '.$i,
                    'address' => $faker->address(),
                    'bank_code' => $faker->numberBetween(1000000, 2000000),
                    'bank_account' => $faker->numberBetween(1000000, 2000000),
                    'account_number' => $faker->numberBetween(1000000, 2000000),
                    'ncs_number' => $faker->numberBetween(1000000, 2000000),
                    'swift' => $faker->swiftBicNumber(),
                    'payment_system_id' => $i,
                    'is_active' => true,
                    'country_id' => $i,
                ]
            );
        }
    }
}
