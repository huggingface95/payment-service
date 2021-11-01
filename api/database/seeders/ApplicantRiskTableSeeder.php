<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class ApplicantRiskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantRiskTableSeeder::create(['name'=>'Low']);
        ApplicantRiskTableSeeder::create(['name'=>'Medium']);
        ApplicantRiskTableSeeder::create(['name'=>'High']);
    }
}
