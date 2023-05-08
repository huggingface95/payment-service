<?php

namespace Database\Seeders;

use App\Models\TransferSwift;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TransferSwiftsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        TransferSwift::firstOrCreate([
            'transfer_id' => 1,
            'transfer_type' => 'TransferIncoming',
            'swift' => $faker->swiftBicNumber(),
            'bank_name' => 'Bank ' . $faker->name(),
            'bank_address' => $faker->address(),
            'bank_country_id' => 5,
            'location' => $faker->city(),
            'ncs_number' => $faker->randomNumber(9),
            'account_number' => $faker->swiftBicNumber(),
        ]);

        TransferSwift::firstOrCreate([
            'transfer_id' => 2,
            'transfer_type' => 'TransferOutgoing',
            'swift' => $faker->swiftBicNumber(),
            'bank_name' => 'Bank ' . $faker->name(),
            'bank_address' => $faker->address(),
            'bank_country_id' => 3,
            'location' => $faker->city(),
            'ncs_number' => $faker->randomNumber(9),
            'account_number' => $faker->swiftBicNumber(),
        ]);

        for ($i = 3; $i <= 10; $i++) {
            TransferSwift::firstOrCreate([
                'transfer_id' => $i,
                'transfer_type' => $faker->randomElement(['TransferOutgoing', 'TransferIncoming']),
                'swift' => $faker->swiftBicNumber(),
                'bank_name' => 'Bank ' . $faker->name(),
                'bank_address' => $faker->address(),
                'bank_country_id' => $i,
                'location' => $faker->city(),
                'ncs_number' => $faker->randomNumber(9),
                'account_number' => $faker->swiftBicNumber(),
            ]);
        }

        TransferSwift::firstOrCreate([
            'transfer_id' => 14,
            'transfer_type' => 'TransferIncoming',
            'swift' => $faker->swiftBicNumber(),
            'bank_name' => 'Bank ' . $faker->name(),
            'bank_address' => $faker->address(),
            'bank_country_id' => 3,
            'location' => $faker->city(),
            'ncs_number' => $faker->randomNumber(9),
            'account_number' => $faker->swiftBicNumber(),
        ]);
    }
}
