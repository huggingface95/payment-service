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

        $regionCountries = [
            1 => [57, 144, 236],  // North America
            2 => [11, 32, 49],   // South America
            3 => [76, 83, 110],  // Europe
        ];


        foreach ($regions as $region) {
            $countryIds = $regionCountries[$region->id] ?? [];
            $countries = Country::whereIn('id', $countryIds)->get();

            foreach ($countries as $country) {
                RegionCountry::create([
                    'region_id' => $region->id,
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
