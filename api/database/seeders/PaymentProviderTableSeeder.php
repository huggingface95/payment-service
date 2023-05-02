<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PaymentProviderTableSeeder extends Seeder
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
            PaymentProvider::withTrashed()->firstOrCreate(
                [
                    'name' => 'PaymentProvider ' . $i,
                ],
                [
                    'description' => $faker->text(100),
                    'is_active' => true,
                    'company_id' => $i,
                ]
            );

            PaymentProvider::withTrashed()->firstOrCreate(
                [
                    'name' => 'Internal',
                    'company_id' => $i,
                ],
                [
                    'is_active' => true,
                ]
            );

        }
    }
}
