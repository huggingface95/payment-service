<?php

namespace Database\Seeders;

use App\Models\ApplicantStatus;
use Illuminate\Database\Seeder;

class ApplicantStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantStatus::create(['name'=>'Requested']);
        ApplicantStatus::create(['name'=>'Declined']);
        ApplicantStatus::create(['name'=>'Approved']);
        ApplicantStatus::create(['name'=>'Pending']);
    }
}
