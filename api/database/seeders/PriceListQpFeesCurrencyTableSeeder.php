<?php

namespace Database\Seeders;

use App\Models\Currencies;
use App\Models\PriceListQpFee;
use App\Models\PriceListQpFeeCurrency;
use Illuminate\Database\Seeder;

class PriceListQpFeesCurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priceListFees = PriceListQpFee::limit(10)->get();
        $currencies = Currencies::limit(10)->get();

        $priceListQpFeeCurrency = [];
        $tmpFee = [
            [
                'fee' => 25,
                'mode' => 'Fix',
            ], [
                'mode' => 'Percent',
                'percent' => 25,
            ],
        ];

        foreach ($priceListFees as $price) {
            foreach ($currencies as $currency) {
                $tmpFee[0]['fee'] = rand(1, 10);
                $tmpFee[1]['percent'] = rand(1, 5);

                $priceListQpFeeCurrency[] = [
                    'price_list_qp_fee_id' => $price->id,
                    'fee' => collect($tmpFee),
                    'currency_id' => $currency->id,
                ];
            }
        }

        foreach ($priceListQpFeeCurrency as $id => $priceListFeesItem) {
            PriceListQpFeeCurrency::firstOrCreate([
                'price_list_qp_fee_id' => $priceListFeesItem['price_list_qp_fee_id'],
                'currency_id' => $priceListFeesItem['currency_id'],
            ], $priceListFeesItem);
        }
    }
}
