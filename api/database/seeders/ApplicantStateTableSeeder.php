<?php

namespace Database\Seeders;

use App\Models\ApplicantState;
use Illuminate\Database\Seeder;

class ApplicantStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantState::create(['name'=>'Active']);
        ApplicantState::create(['name'=>'Suspended']);
        ApplicantState::create(['name'=>'Blocked']);
    }
}
