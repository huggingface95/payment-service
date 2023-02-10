<?php

namespace Database\Seeders;

use App\Models\ApplicantKycLevel;
use Illuminate\Database\Seeder;

class ApplicantKycLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kycLevels = [
            'Low',
            'Medium',
            'High',
        ];

        foreach ($kycLevels as $level) {
            ApplicantKycLevel::firstOrCreate([
                'name' => $level,
            ]);
        }
    }
}
