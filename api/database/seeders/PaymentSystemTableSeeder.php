<?php

namespace Database\Seeders;

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
            PaymentSystem::query()->firstOrCreate([
                'name' => $faker->company().'Pay',
                'is_active' => true,
                'payment_provider_id' => $i,
            ]);

            PaymentSystem::query()->firstOrCreate([
                'name' => 'Internal',
                'is_active' => true,
                'payment_provider_id' => $i,
            ]);
        }
    }
}
