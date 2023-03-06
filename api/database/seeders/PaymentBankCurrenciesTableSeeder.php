<?php

namespace Database\Seeders;

use App\Models\PaymentBankCurrency;
use Illuminate\Database\Seeder;

class PaymentBankCurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentBankCurrency::firstOrCreate([
            'payment_bank_id' => 1,
            'currency_id' => 1,
        ]);
    }
}
