<?php

namespace Database\Seeders;

use App\Models\PaymentProviderIban;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PaymentProviderIbanTableSeeder extends Seeder
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
            PaymentProviderIban::firstOrCreate(
                [
                    'name' => 'PaymentProviderIBAN '.$i,
                ],
                [
                    'swift' => $faker->swiftBicNumber(),
                    'sort_code' => $faker->randomNumber(6),
                    'provider_address' => $faker->address(),
                    'about' => $faker->text(30),
                    'company_id' => $i,
                    'currency_id' => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}
