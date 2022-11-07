<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::firstOrCreate([
            'id' => 1,
            'name' => 'TestRegion',
            'company_id' => 1,
        ]);
    }
}
