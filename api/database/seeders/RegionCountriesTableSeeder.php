<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use App\Models\RegionCountry;
use Illuminate\Database\Seeder;

class RegionCountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegionCountry::unsetEventDispatcher();

        $regions = Region::all();
        $countries = Country::all();

        foreach ($regions as $region) {
            foreach ($countries as $country) {
                RegionCountry::create([
                    'region_id' => $region->id,
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
