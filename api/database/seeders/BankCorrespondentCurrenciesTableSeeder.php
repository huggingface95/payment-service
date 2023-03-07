<?php

namespace Database\Seeders;

use App\Models\BankCorrespondentCurrency;
use Illuminate\Database\Seeder;

class BankCorrespondentCurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankCorrespondentCurrency::firstOrCreate([
            'bank_correspondent_id' => 1,
            'currency_id' => 1,
        ]);
    }
}
