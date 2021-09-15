<?php

namespace Database\Seeders;

use App\Models\Languages;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/data/language_iso.csv';
        $dataCsv = array_map('str_getcsv', file($path));
        foreach ($dataCsv as $item) {
            Languages::create([
                    'name' => $item[1],
                    'iso' => $item[0]
                ]
            );
        }
    }
}
