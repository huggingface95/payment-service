<?php

namespace Database\Seeders;

use App\Models\Currencies;
use App\Models\PriceListFee;
use App\Models\PriceListFeeCurrency;
use Illuminate\Database\Seeder;

class PriceListFeeCurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priceListFees = PriceListFee::get();
        $currencies = Currencies::limit(10)->get();

        $priceListFeeCurrency = [
            [
                'price_list_fee_id' => $priceListFees[0]->id,
                'fee' => collect([
                    [
                        'fee' => 2,
                        'mode' => 'Fix',
                    ], [
                        'mode' => 'Percent',
                        'percent' => 10,
                    ],
                ]),
                'currency_id' => $currencies[0]->id,
            ],
            [
                'price_list_fee_id' => $priceListFees[0]->id,
                'fee' => collect([
                    [
                        'fee' => 25,
                        'mode' => 'Fix',
                    ], [
                        'mode' => 'Percent',
                        'percent' => 25,
                    ],
                ]),
                'currency_id' => $currencies[0]->id,
            ],
            [
                'price_list_fee_id' => $priceListFees[0]->id,
                'fee' => collect([
                    [
                        'fee' => 6,
                        'mode' => 'Fix',
                    ],
                    [
                        'mode' => 'Range',
                        'amount_from' => 400,
                        'amount_to' => 500,
                    ],
                    [
                        'mode' => 'Percent',
                        'percent' => 10,
                    ],
                ]),
                'currency_id' => $currencies[0]->id,
            ],
            [
                'price_list_fee_id' => $priceListFees[0]->id,
                'fee' => collect([
                    [
                        'fee' => 34,
                        'mode' => 'Fix',
                    ],
                    [
                        'mode' => 'Range',
                        'amount_from' => 100,
                        'amount_to' => 300,
                    ],
                    [
                        'mode' => 'Percent',
                        'percent' => 22,
                    ],
                ]),
                'currency_id' => $currencies[1]->id,
            ],
            [
                'price_list_fee_id' => $priceListFees[1]->id,
                'fee' => collect([
                    [
                        'fee' => 6,
                        'mode' => 'Fix',
                    ],
                    [
                        'mode' => 'Range',
                        'amount_from' => 400,
                        'amount_to' => 500,
                    ],
                    [
                        'mode' => 'Percent',
                        'percent' => 10,
                    ],
                ]),
                'currency_id' => $currencies[0]->id,
            ],
        ];

        foreach ($priceListFeeCurrency as $id => $priceListFeesItem) {
            PriceListFeeCurrency::firstOrCreate([
                'id' => $id + 1,
            ], $priceListFeesItem);
        }
    }
}
