<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentBankCurrenciesRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_bank_currencies_regions')->insert([
            'payment_bank_id' => 1,
            'currency_id' => 1,
            'region_id' => 1,
        ]);
    }
}
