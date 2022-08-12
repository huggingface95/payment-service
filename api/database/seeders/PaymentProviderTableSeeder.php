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
            $paymentProvider = PaymentProvider::create(
                [
                    'id'        => $i,
                    'name'     => 'PaymentProvider'.$i,
                    'description' => $faker->text(100),
                    'is_active' => true,
                    'company_id' => $i,
                ]
            );
            //$paymentProvider->countries()->attach([$faker->numberBetween(1, 100)]);
            //$paymentProvider->currencies()->attach([$faker->numberBetween(1, 100)]);
            $paymentProvider->paymentSystems()->attach([$faker->numberBetween(1, 10)]);
        }
    }
}
