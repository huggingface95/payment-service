<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Faker\Factory;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $regions = [
            'North Americ',
            'South America',
            'Europe',
            'Central America',
        ];

        foreach ($regions as $region) {
            Region::query()->firstOrCreate([
                'name' => $region,
                'company_id' => 1,
            ]);
        }
    }
}
