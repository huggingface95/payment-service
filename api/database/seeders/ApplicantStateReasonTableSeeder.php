<?php

namespace Database\Seeders;

use App\Models\ApplicantStateReason;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ApplicantStateReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantStateReason::create(['name'=>'Kyc']);
        ApplicantStateReason::create(['name'=>'Documents Expired']);
        ApplicantStateReason::create(['name'=>'Financial Monitoring']);
    }
}
