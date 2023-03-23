<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankCorrespondentCurrenciesRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bank_correspondent_currencies_regions')->insert([
            'bank_correspondent_id' => 1,
            'currency_id' => 1,
            'region_id' => 1,
        ]);
    }
}
