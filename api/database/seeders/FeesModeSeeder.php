<?php

namespace Database\Seeders;

use App\Models\FeesMode;
use Illuminate\Database\Seeder;

class FeesModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feesMode = ['Fix','Range','%'];
        foreach ($feesMode as $item) {
            FeesMode::firstOrCreate(['name'=>$item]);
        }
    }
}
