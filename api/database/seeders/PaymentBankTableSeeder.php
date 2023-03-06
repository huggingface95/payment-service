<?php

namespace Database\Seeders;

use App\Models\PaymentBank;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PaymentBankTableSeeder extends Seeder
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
            PaymentBank::firstOrCreate(
                [
                    'id' => $i,
                ],
                [
                    'name' => 'Bank '.$i,
                    'address' => $faker->address(),
                    'bank_code' => $faker->numberBetween(1000000, 2000000),
                    'payment_system_code' => $faker->numberBetween(300000000, 400000000),
                    'is_active' => true,
                    'payment_provider_id' => $i,
                    'payment_system_id' => $i,
                ]
            );
        }
    }
}
