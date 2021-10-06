<?php

namespace Database\Seeders;


use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/data/currency_codes.csv';
        $dataCsv = array_map('str_getcsv', file($path));
        foreach ($dataCsv as $item) {
            Currency::create(
                [
                    'name'=> $item[0],
                    'code' => $item[1],
                    'minor_unit' => $item[2]
                ]
            );
        }
    }
}
