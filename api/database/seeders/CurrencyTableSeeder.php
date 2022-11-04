<?php

namespace Database\Seeders;

use App\Models\Currencies;
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
        $path = base_path() . '/data/currency_codes.csv';
        $dataCsv = array_map('str_getcsv', file($path));

        foreach ($dataCsv as $item) {
            Currencies::firstOrCreate(
                [
                    'code' => $item[1],
                ],
                [
                    'name' => $item[0],
                    'minor_unit' => $item[2],
                ]
            );
        }
    }
}
