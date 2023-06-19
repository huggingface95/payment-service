<?php

namespace Database\Seeders;

use App\Models\Currencies;
use App\Models\PriceListQpFeeCurrency;
use App\Models\PriceListQpFeeDestinationCurrency;
use Illuminate\Database\Seeder;

class PriceListQpFeesDestinationCurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fees = PriceListQpFeeCurrency::get();
        $currencies = Currencies::limit(10)->get();

        foreach ($fees as $fee) {
            foreach ($currencies as $currency) {
                PriceListQpFeeDestinationCurrency::updateOrCreate([
                    'price_list_qp_fee_currency_id' => $fee->id,
                    'currency_id' => $currency->id,
                ]);
            }
        }
    }
}
