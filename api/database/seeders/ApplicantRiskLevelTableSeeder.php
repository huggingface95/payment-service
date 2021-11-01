<?php

namespace Database\Seeders;


use App\Models\ApplicantRiskLevel;
use Illuminate\Database\Seeder;

class ApplicantRiskLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantRiskLevel::create(['name'=>'Low']);
        ApplicantRiskLevel::create(['name'=>'Medium']);
        ApplicantRiskLevel::create(['name'=>'High']);
    }
}
