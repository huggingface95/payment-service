<?php

namespace Database\Seeders;

use App\Models\ApplicantIndividualLabel;
use Illuminate\Database\Seeder;

class ApplicantLabelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantIndividualLabel::create(['name'=>'Address Not Complied', 'hex_color_code'=>'#cccccc']);
        ApplicantIndividualLabel::create(['name'=>'Personal Info Verified', 'hex_color_code'=>'#0e6548']);
    }
}
