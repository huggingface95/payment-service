<?php

namespace Database\Seeders;

use App\Models\FeeType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert(['name' => 'TestRegion', 'company_id' => 1]);
    }
}
