<?php

namespace Database\Seeders;

use App\Models\FeePeriod;
use Illuminate\Database\Seeder;

class FeePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feesPeriod = ['Each time','One time','Daily','Weekly','Monthly','Yearly'];
        foreach ($feesPeriod as $item) {
            FeePeriod::firstOrCreate(['name'=>$item]);
        }
    }
}
