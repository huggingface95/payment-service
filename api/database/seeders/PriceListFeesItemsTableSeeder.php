<?php

namespace Database\Seeders;

use App\Models\Currencies;
use App\Models\FeeMode;
use App\Models\PriceListFee;
use App\Models\PriceListFeesItem;
use Illuminate\Database\Seeder;

class PriceListFeesItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priceListFees = PriceListFee::get();
        $feeModes = FeeMode::get();
        $currencies = Currencies::limit(10)->get();

        $priceListFeesItems = [
            [
                'price_list_fees_id' => $priceListFees[0]->id,
                'fee_mode_id' => $feeModes[0]->id,
                'fee' => 10,
                'fee_from' => 100,
                'fee_to' => 150,
                'currency_id' => $currencies[0]->id,
            ],
            [
                'price_list_fees_id' => $priceListFees[0]->id,
                'fee_mode_id' => $feeModes[1]->id,
                'fee' => 20,
                'currency_id' => $currencies[0]->id,
            ],
            [
                'price_list_fees_id' => $priceListFees[1]->id,
                'fee_mode_id' => $feeModes[2]->id,
                'fee' => 30,
                'currency_id' => $currencies[1]->id,
            ],
        ];

        foreach ($priceListFeesItems as $priceListFeesItem) {
            PriceListFeesItem::create($priceListFeesItem);
        }
    }
}
