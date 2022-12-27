<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use App\Models\PaymentSystem;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PaymentSystemTableSeeder extends Seeder
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
            PaymentSystem::firstOrCreate([
                'id' => $i,
            ], [
                'name' => $faker->company() . 'Pay',
                'is_active' => true,
                'payment_provider_id' => $i,
            ]);
        }

        PaymentSystem::firstOrCreate([
            'name' => 'Internal',
            'is_active' => true,
            'payment_provider_id' => PaymentProvider::where('name', 'Internal')->first()->id,
        ]);
    }
}
