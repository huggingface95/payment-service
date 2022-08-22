<?php

namespace Database\Seeders;

use App\Models\FeeType;
use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feeType = ['Fees', 'Service fee'];
        foreach ($feeType as $item) {
            FeeType::firstOrCreate(['name'=>$item]);
        }
    }
}
