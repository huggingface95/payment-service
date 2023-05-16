<?php

namespace Database\Seeders;

use App\Models\Currencies;
use App\Models\CurrencyExchangeRate;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CurrencyExchangeRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = Currencies::all()->pluck('id')->toArray();
        $faker = Factory::create();

        foreach ($currencies as $currency) {
            CurrencyExchangeRate::updateOrCreate(
                [
                    'currency_from_id' => 1,
                    'currency_to_id' => $currency,
                ],
                [
                    'rate' => $faker->randomFloat('2', 0, 2),
                    'quote_provider_id' => 1,
                ]
            );
            CurrencyExchangeRate::updateOrCreate(
                [
                    'currency_from_id' => 2,
                    'currency_to_id' => $currency,
                ],
                [
                    'rate' => $faker->randomFloat('2', 0, 2),
                    'quote_provider_id' => 1,
                ]
            );
        }
    }
}
