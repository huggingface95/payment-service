<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/data/country_iso.csv';
        $dataCsv = array_map('str_getcsv', file($path));
        foreach ($dataCsv as $item) {
            Country::create([
                    'name' => $item[0],
                    'iso' => $item[1]
                ]
            );
        }
    }
}
