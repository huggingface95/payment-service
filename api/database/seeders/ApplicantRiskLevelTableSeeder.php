<?php

namespace Database\Seeders;

use App\Enums\ApplicantRiskLevelEnum;
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
        $riskLevels = ApplicantRiskLevelEnum::cases();

        foreach ($riskLevels as $level) {
            ApplicantRiskLevel::firstOrCreate([
                'name' => $level->toString(),
            ]);
        }
    }
}
