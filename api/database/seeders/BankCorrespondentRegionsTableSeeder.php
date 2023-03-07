<?php

namespace Database\Seeders;

use App\Models\BankCorrespondentRegion;
use Illuminate\Database\Seeder;

class BankCorrespondentRegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankCorrespondentRegion::firstOrCreate([
            'bank_correspondent_id' => 1,
            'region_id' => 1,
        ]);
    }
}
